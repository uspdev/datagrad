<?php

namespace App\Models;

use App\Casts\AlwaysArray;
use App\Casts\BrazilianDate;
use App\Replicado\Graduacao;
use App\Replicado\Pessoa;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class Disciplina extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * $responsaveis = ['codpes'= > 'numnum', 'nompesttd' => 'nomnomnom'];
     * $habilidades = ['hab 1', 'hab 2', 'hab x ...'];
     * $competencias = similar a habilidades;
     *
     * @var array
     */
    protected $casts = [
        'responsaveis' => AlwaysArray::class,
        'habilidades' => AlwaysArray::class,
        'competencias' => AlwaysArray::class,
        'mtdens' => AlwaysArray::class,
        'mtdensigl' => AlwaysArray::class,
        'objdslsut' => AlwaysArray::class,
        'pdf' => AlwaysArray::class,
        'historico' => AsCollection::class,
        'dtainivalprp' => BrazilianDate::class,
        'dtafimvalprp' => BrazilianDate::class,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'historico' => '[]', // se for null retorna array para facilitar no froeach
    ];

    /**
     * O histórico é no formato [estado, data, user (nome), comentario (opcional)]
     */

    // tipdis: acho que deve ir para o replicado ***
    public static $tipdis = [
        'S' => 'Semestral',
        'A' => 'Anual',
        'Q' => 'Quadrimestral',
    ];

    /**
     * Estado da disciplina
     *
     * propor alteração - quando o objeto é criado mas não foi salvo: ainda não foi proposto a alteração
     */
    public static $estados = ['Criar', 'Propor alteração', 'Em edição', 'Em aprovação', 'Aprovado', 'Finalizado'];

    /** Dados que vem do replicado */
    public $dr;

    /** Cursos da unidade para os quais a disciplina é ministrada */
    public $cursos;

    /**
     * Dados dos campos do formulário de alteração de disciplina
     */
    public static function meta()
    {
        return config('datagrad.meta');
    }

    // protected function pdf(): Attribute
    // {
    //     return Attribute::make(
    //         get: function(string $value = null) {
    //         return json_decode($value, true) ?? ['filename' =>'', 'date'=> '', 'hash'=>''];
    //         },
    //     );
    // }

    /**
     * Retorna dados replicados da diciplina, incluindo responsaveis, e cursos
     *
     * @param String $coddis
     */
    public static function obterDisciplinaReplicado($coddis, $verdis = 'max')
    {
        $dr = Graduacao::obterDisciplina($coddis, $verdis);
        if ($dr) {
            $dr['responsaveis'] = Graduacao::listarResponsaveisDisciplina($coddis);
            $dr['cursos'] = Graduacao::listarCursosDisciplina($coddis);
            return $dr;
        }
        return [];
    }

    /**
     * Retorna a disciplina existente pelo código, ou cria uma nova se não existir.
     *
     * - Se a disciplina existir e tiver versão (`verdis`), usa essa versão.
     * - Se existir mas não tiver versão, usa 'max'.
     * - Se não existir, cria nova e marca estado como 'Propor alteração'.
     * - Se nenhum código for informado, cria nova disciplina e marca estado como 'criar'.
     *
     * @param  string|null  $coddis  Código da disciplina
     * @return self
     */
    public static function primeiroOuNovo(?string $coddis = null): self
    {
        // criar nova disciplina
        if (!$coddis) {
            $disc = self::novo();
            $disc->estado = 'Criar';
            return $disc;
        }

        // obter disciplina local
        $disc = self::where('coddis', $coddis)
            ->where(function ($q) {
                $q->naoFinalizado()->orWhereNull('estado');
            })
            ->first();

        $verdis = $disc ? $disc->verdis : 'max';
        $dr = self::obterDisciplinaReplicado($coddis, $verdis);

        // existe no bd mas não existe no replicado
        // if ($disc && !$dr) {
        //     $disc->estado = 'Criar';
        // }

        // nao existe no bd mas existe no replicado
        if (!$disc && $dr) {
            $disc = self::novo($dr);
            $disc->estado = 'Propor alteração';
        }

        // nao existe no bd nem no replicado
        if (!$disc && !$dr) {
            $disc = self::novo();
            $disc->coddis = $coddis;
            $disc->tipdis = null;
            $disc->codlinegr = null;
            $disc->estado = 'Criar';
        }

        // dd($disc->tipdis, $dr);
        $disc->dr = $dr;

        return $disc;
    }

    public static function listarDisciplinasFinalizadas()
    {
        return self::where('estado', 'Finalizado')->get();
    }

    /**
     * Cria nova disciplina e preenche com valores padrão, sem persistir no BD
     *
     * @param $dr Se informado preenche o objeto com os dados
     */
    public static function novo($dr = null)
    {
        $disc = new Disciplina();

        if ($dr) {
            // precisa remover elementos do replicado que são array e não estão no BD
            $disc->fill(array_diff_key($dr, array_flip(['responsaveis', 'cursos'])));

            $disc->atividade_extensionista = $dr['cgahoratvext'] ? true : false;

            $resps = [];
            foreach ($dr['responsaveis'] as $r) {
                $resps[] = ['codpes' => $r['codpes'], 'nompesttd' => $r['nompesttd']];
            }
            $disc->responsaveis = $resps;
        } else {
            $disc->responsaveis = [['codpes' => Auth::user()->codpes, 'nompesttd' => Pessoa::obterNome(Auth::user()->codpes)]];
        }
        $disc->atualizado_por_id = Auth::user()->id;
        $disc->criado_por_id = Auth::user()->id;
        $disc->updated_at = now();
        $disc->created_at = now();

        return $disc;
    }

    /**
     * Mescla os responsáveis e cria o campo status para saber se é novo, mesmo ou removido
     */
    public function mesclarResponsaveisReplicado()
    {
        $resps = [];
        $dr = $this->dr;
        foreach ($dr['responsaveis'] ?? [] as $r) {
            if (strpos(json_encode($this->responsaveis), $r['codpes']) !== false) {
                // está no replicado e está no novo => mesmo
                $resps[] = ['codpes' => $r['codpes'], 'nompesttd' => $r['nompesttd'], 'status' => 'mesmo'];
            } else {
                // esta no replicado mas não está no novo => removido
                $resps[] = ['codpes' => $r['codpes'], 'nompesttd' => $r['nompesttd'], 'status' => 'removido'];
            }
        }

        foreach ($this->responsaveis as $r) {
            if (strpos(json_encode($dr['responsaveis'] ?? []), $r['codpes']) !== false) {
                // esta no novo e está no replicado => já foi inserido
            } else {
                // esta no novo mas não está no replicado => novo
                $resps[] = ['codpes' => $r['codpes'], 'nompesttd' => $r['nompesttd'], 'status' => 'novo'];
            }
        }

        usort($resps, function ($a, $b) {
            return $a['nompesttd'] > $b['nompesttd'];
        });

        $this->responsaveis = $resps;
        return true;
    }

    /**
     * Adiciona um novo responsável ao objeto
     */
    public function adicionarResponsavel($resp)
    {
        $resps = $this->responsaveis;
        if (strpos(json_encode($resps), $resp) === false) {
            $resps[] = ['codpes' => $resp, 'nompesttd' => Pessoa::obterNome($resp)];
        }
        usort($resps, function ($a, $b) {
            return $a['nompesttd'] > $b['nompesttd'];
        });
        $this->responsaveis = $resps;
    }

    /**
     * Remove um responsável do objeto
     */
    public function removerResponsavel($resp)
    {
        $resps = $this->responsaveis;
        foreach ($resps as $k => $r) {
            if ($r['codpes'] == $resp) {
                unset($resps[$k]);
                break;
            }
        }
        usort($resps, function ($a, $b) {
            return $a['nompesttd'] > $b['nompesttd'];
        });
        $this->responsaveis = $resps;
    }

    /**
     * Lista todas as diciplinas sob responsabilidade de $codpes
     *
     * Vai incluir as disciplinas do replicado e as disciplinas locais, mas sem repetição
     */
    public static function listarDisciplinasPorResponsavel($codpes)
    {
        $drs = Graduacao::listarDisciplinasPorResponsavel($codpes);
        $discs = self::mergearResponsaveis(collect(), $drs);

        $discsLocal = self::naoFinalizado()->responsavel($codpes)->get();
        $discs = self::mergearDisciplinas($discs, $discsLocal);

        return $discs;
    }

    /**
     * Lista todas as disciplinas da unidade - visão CG
     *
     * Guarda em cache do Laravel por 12h pois é uma consulta demorada.
     * O cache é renovado quando há alteração da diciplina no sistema passando $refresh = true
     */
    public static function listarDisciplinas($refresh = true)
    {
        if ($refresh) {
            Cache::forget('listarDisciplinas');
        }

        $discs = Cache::remember('listarDisciplinas', 60 * 60 * 4, function () {
            $drs = Graduacao::listarDisciplinas();
            $discs = self::mergearResponsaveis(collect(), $drs);

            $discsLocal = self::naoFinalizado()->get();
            $discs = self::mergearDisciplinas($discs, $discsLocal);

            return $discs;
        });

        return $discs;
    }

    /**
     * Lista disciplinas por prefixo, com os respectivos responsáveis.
     *
     * O prefixo define o departamento de orígem da disciplina.
     * Ex.: SET0199, o prefixo é SET
     *
     * @param String $prefixo
     * @return \Illuminate\Support\Collection
     */
    public static function listarDisciplinasPorPrefixo(string|array $prefixo)
    {
        $prefixos = (array) $prefixo;
        $drs = Graduacao::listarDisciplinasPorPrefixo($prefixos);
        $discs = self::mergearResponsaveis(collect(), $drs);

        $discsLocal = self::prefixo($prefixos)->naoFinalizado()->get();
        $discs = self::mergearDisciplinas($discs, $discsLocal);

        return $discs;
    }

    public static function renovarCacheAfterResponse()
    {
        // renova o cache depois de enviar o response para o navegador
        // https://laravel.com/docs/10.x/queues#dispatching-after-the-response-is-sent-to-browser
        dispatch(function () {
            self::listarDisciplinas(true);
        })->afterResponse();
    }

    /**
     * Pega array de disciplinas do replicado, transforma em objeto e mescla responsaveis
     */
    protected static function mergearResponsaveis($discs, $drs)
    {
        if (empty($drs)) {
            return $discs;
        }
        $codigos = collect($drs)
            ->pluck('coddis')
            ->unique()
            ->values()
            ->all();

        $responsaveisPorCoddis = collect(Graduacao::listarResponsaveisDisciplina($codigos))
            ->groupBy('coddis');

        foreach ($drs as $dr) {
            $disc = new self();
            $disc->fill($dr);
            $disc->dr = $dr;
            $disc->responsaveis = $responsaveisPorCoddis[$disc->coddis] ?? [];
            $discs[] = $disc;
        }

        return $discs;
    }

    /**
     * Limpa de $discs as disciplinas repetidas em $discsLocal
     */
    protected static function mergearDisciplinas($discs, $discsLocal)
    {
        $replicados = $discs->keyBy('coddis');
        $result = collect();

        foreach ($discsLocal as $discLocal) {
            if ($dr = $replicados->get($discLocal->coddis)) {
                $discLocal->dr = $dr; // associa versão do replicado
                $replicados->forget($discLocal->coddis); // remove para evitar duplicidade
            }
            $result->push($discLocal);
        }

        // adiciona disciplinas existentes apenas no replicado
        return $result->concat($replicados->values())->values();
    }

    /**
     * Lista os nomes das visoes que o usuário pode ter sobre as disciplinas
     */
    // public static function listarVisoes()
    // {
    //     $ret = [];
    //     if (Gate::check('senhaunica.docente')) {
    //         $ret[] = 'docente';
    //     }
    //     if (Gate::check('disciplina-cg')) {
    //         $ret[] = 'cg';
    //     }
    //     return $ret;
    // }

    /**
     * Retorna string com lista de responsáveis separados por vírgula (view)
     */
    public function retornarResponsaveis($dr = false)
    {
        $ret = '';
        $src = $dr ? $this->dr['responsaveis'] ?? '-' : $this->responsaveis;
        foreach ($src as $k => $r) {
            $ret .= $r['nompesttd'] . ', ';
        }
        return substr($ret, 0, -2);
    }

    /**
     * Retorna o tipo da disciplina textual com base no tipdis (view)
     *
     * @param Bool $dr Se true pega do replicado, se false pega da base local
     * @return String
     */
    public function tipdis($dr = false)
    {
        if ($dr) {
            $ret = self::$tipdis[$this->dr['tipdis']];
        } else {
            $ret = self::$tipdis[$this->tipdis] ?? '-';
        }
        return $ret;

        // return $dr ? self::$tipdis[$this->dr['tipdis']] : self::$tipdis[$this->tipdis];
    }

    /**
     * Calcula um hash dos dados que vão para o PDF
     */
    public function hash()
    {
        // colunas desconsideradas no hash
        $removeKeys = ['pdf', 'estado', 'criado_por_id', 'atualizado_por', 'atualizado_por_id', 'created_at', 'updated_at', 'deleted_at', 'diffs', 'historico'];

        $data = $this->toArray();
        $data = array_diff_key($data, array_flip($removeKeys));

        // mostra as colunas sendo consideradas
        // dd(implode('","',array_keys($data)));

        // na visualização o resposável tem um campo a mais de status que deve ser removido
        $resps = [];
        foreach ($data['responsaveis'] as $resp) {
            $resps[] = ['codpes' => $resp['codpes'], 'nompesttd' => $resp['nompesttd']];
        }
        $data['responsaveis'] = $resps;
        $hash = md5(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $hash;
    }

    public function atualizarEstado($estado, $comentario = '')
    {
        if (empty($this->estado) || $this->estado != $estado) {
            $novo = [
                'estado' => $estado,
                'data' => now()->format('d/m/Y H:i'),
                'user' => Auth::user()->name,
                'comentario' => $comentario,
            ];
            $historico = $this->historico;
            $historico[] = $novo;
            $this->historico = $historico;
            $this->estado = $estado;
        }
    }

    /**
     * Valida os campos da disciplina de acordo com as regras definidas na função meta()
     *
     * Retorna um array de mensagens de erro, ou vazio se estiver tudo ok
     * 'rules' default é 'required'
     */
    public function validarCampos()
    {
        $rules = [];
        $attributes = [];
        foreach (self::meta() as $campo => $config) {
            $rules[$campo] = $config['rules'] ?? 'required';
            $attributes[$campo] = $config['titulo'] ?? $campo;
        }

        $validator = Validator::make($this->toArray(), $rules, [], $attributes);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        return [];
    }

    /**
     * Retorna se a disciplina está em um estado editável ou não
     *
     * @return boolean
     */
    public function isEditavel()
    {
        return $this->estado == 'Em edição' || $this->estado == 'Propor alteração' || $this->estado == 'Criar';
    }

    /**
     * Método genérico para retornar itens marcados (habilidades ou competências)
     *
     * @param string $codcur Código do curso
     * @param string $colDisc Coluna do modelo Disciplina (array JSON)
     * @param string $colCurso Coluna do modelo Curso em português
     * @param string|null $colCursoIgl Coluna do modelo Curso em inglês (opcional)
     * @param string $prefix Prefixo para numerar (H ou C)
     * @param bool $ingles Se true, retorna versão em inglês
     * @return array
     */
    protected function marcarItens(string $codcur, string $colDisc, bool $ingles = false): array
    {
        $curso = Curso::where('codcur', $codcur)->first();
        if (!$curso) {
            return [];
        }

        // Determina o prefix automaticamente
        $prefix = match ($colDisc) {
            'habilidades' => 'H',
            'competencias' => 'C',
            default => 'I',
        };

        // Colunas do curso
        $colCursoPt = $colDisc;
        $colCursoEn = $colDisc . '_igl';

        // Todas as linhas
        $todasPt = preg_split('/\r\n|\r|\n/', trim($curso->{$colCursoPt}));
        $todasEn = $ingles && isset($curso->{$colCursoEn}) ? preg_split('/\r\n|\r|\n/', trim($curso->{$colCursoEn})) : [];

        // Itens escolhidos pela disciplina
        $escolhidas = $this->{$colDisc}[$codcur] ?? [];

        $resultado = [];
        foreach ($todasPt as $i => $itemPt) {
            if (in_array($itemPt, $escolhidas)) {
                $valor = $ingles ? $todasEn[$i] ?? '' : $itemPt;
                if (!empty(trim($valor))) {
                    $resultado[] = $prefix . ($i + 1) . '. ' . $valor . ';';
                }
            }
        }

        return $resultado;
    }

    /**
     * Retorna os dados para configurar o botão de ação da disciplina, com base no estado atual
     */
    public function obterEstadoConfig()
    {
        return match ($this->estado) {
            'Criar' => [
                'route' => 'disciplinas.edit',
                'class' => 'btn-outline-success',
                'label' => 'Criação | ' . $this->updated_at->format('d/m/Y'),
                'order' => '1 ' . $this->updated_at->format('Y-m-d H:i:s'),
            ],
            'Em edição' => [
                'route' => 'disciplinas.edit',
                // acho q o primary não usa mais. pois agora tem o criar. masaki 11/5/2026.
                'class' => $this->dr ? 'btn-outline-warning' : 'btn-outline-primary',
                'label' => 'Edição | ' . $this->updated_at->format('d/m/Y'),
                'order' => '2 ' . $this->updated_at->format('Y-m-d H:i:s'),
            ],
            'Em aprovação' => [
                'route' => 'disciplinas.preview-html',
                'class' => 'btn-outline-danger',
                'label' => 'Aprovação | ' . $this->updated_at->format('d/m/Y'),
                'order' => '3 ' . $this->updated_at->format('Y-m-d H:i:s'),
            ],
            'Aprovado' => [
                'label' => 'Aprovado | ' . $this->updated_at->format('d/m/Y'),
                'order' => '4 ' . $this->updated_at->format('Y-m-d H:i:s'),
            ],
            'Finalizado' => [
                'class' => 'btn-outline-secondary',
                'label' => 'Finalizado | ' . $this->updated_at->format('d/m/Y'),
                'order' => '5 ' . $this->updated_at->format('Y-m-d H:i:s'),
            ],
            default => null,
        };
    }

    // Habilidades
    public function habilidades(string $codcur): array
    {
        return $this->marcarItens($codcur, 'habilidades');
    }

    public function habilidadesIgl(string $codcur): array
    {
        return $this->marcarItens($codcur, 'habilidades', true);
    }

    // Competências
    public function competencias(string $codcur): array
    {
        return $this->marcarItens($codcur, 'competencias');
    }

    public function competenciasIgl(string $codcur): array
    {
        return $this->marcarItens($codcur, 'competencias', true);
    }

    /**
     * Retorna o estado checked ou não para input type radio das habilidades
     */
    public function checkHabilidades($codcur, $habilidade)
    {
        $habilidades = $this->habilidades;
        if (!empty($habilidades) && !empty($habilidades[$codcur]) && in_array($habilidade, $habilidades[$codcur])) {
            return 'checked';
        }
        return null;
    }

    /**
     * Retorna o estado checked ou não para input type radio das competencias
     */
    public function checkCompetencias($codcur, $competencia)
    {
        $competencias = $this->competencias;
        if (!empty($competencias) && !empty($competencias[$codcur]) && in_array($competencia, $competencias[$codcur])) {
            return 'checked';
        }
        return null;
    }

    /**
     * Retorna a descrição da língua estrangeira a partir do código ISO639-3.
     *
     * @param string|null $codlinegr Código da língua (ISO639-3), por exemplo "ENG", "POR".
     * @return string Nome da língua correspondente ou "-" se não encontrado.
     */
    public function codlinegr($codlinegr)
    {
        return Graduacao::$codlinegr[$codlinegr] ?? '-';
    }


    // ESCOPOS *********************************

    /**
     * Escopo para filtrar disciplinas que não estão finalizadas
     */
    public function scopeNaoFinalizado($query)
    {
        return $query->where('estado', '!=', 'Finalizado');
    }

    /**
     * Escopo para filtrar disciplinas sob responsabilidade de um codpes específico
     */
    public function scopeResponsavel($query, $codpes)
    {
        return $query->where(
            'responsaveis',
            'like',
            "%{$codpes}%"
        );
    }

    /**
     * Escopo para filtrar disciplinas por prefixo
     */
    public function scopePrefixo($query, string|array $prefixos)
    {
        $prefixos = (array) $prefixos;

        return $query->where(function ($q) use ($prefixos) {
            foreach ($prefixos as $prefixo) {
                $q->orWhere('coddis', 'like', "{$prefixo}%");
            }
        });
    }

    // RELACIONAMENTOS *************************

    /**
     * Relacionamento com user:criado por
     */
    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'criado_por_id');
    }

    public function atualizadoPor()
    {
        return $this->belongsTo(User::class, 'atualizado_por_id', 'id');
    }
}
