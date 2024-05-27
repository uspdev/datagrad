<?php

namespace App\Models;

use App\Replicado\Graduacao;
use App\Replicado\Pessoa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class Disciplina extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'responsaveis' => 'array',
        // 'responsaveis' => AsArrayObject::class,
    ];

    /** Dados que vem do replicado */
    public $dr;

    /** Dados dos campos do formulário de alteração de disciplina */
    public $meta = [
        'tipdis' => [
            'titulo' => 'Tipo/Type',
            'diff' => false,
        ],
        'creaul' => [
            'titulo' => 'Créditos-aula/Semester hour credits',
            'diff' => false,
        ],
        'cretrb' => [
            'titulo' => 'Créditos-trabalho/Practice hour credits',
            'diff' => false,
        ],
        'numvagdis' => [
            'titulo' => 'Número de vagas/Number of places',
            'diff' => false,
        ],
        'durdis' => [
            'titulo' => 'Duração (semanas)',
            'diff' => false,
        ],
        'nomdis' => [
            'titulo' => 'Nome',
        ],
        'nomdisigl' => [
            'titulo' => 'Name',
            'class' => 'ingles',
        ],
        'objdis' => [
            'titulo' => 'Objetivos',
        ],
        'objdisigl' => [
            'titulo' => 'Objectives',
            'class' => 'ingles',
        ],
        'pgmrsudis' => [
            'titulo' => 'Programa resumido',
            'ajuda' => 'Resumo do programa da disciplina, para impressão do catálogo de Cursos da Graduação',
        ],
        'pgmrsudisigl' => [
            'titulo' => 'Short program',
            'class' => 'ingles',
        ],
        'pgmdis' => [
            'titulo' => 'Programa completo',
        ],
        'pgmdisigl' => [
            'titulo' => 'Full program',
            'class' => 'ingles',
        ],
        'dscmtdavl' => [
            'titulo' => 'Métodos de avaliação',
        ],
        'dscmtdavligl' => [
            'titulo' => 'Evaluation methods',
            'class' => 'ingles',
        ],
        'crtavl' => [
            'titulo' => 'Critérios de avaliação',
        ],
        'crtavligl' => [
            'titulo' => 'Evaluation criterion',
            'class' => 'ingles',
        ],
        'dscnorrcp' => [
            'titulo' => 'Norma de recuperação',
        ],
        'dscnorrcpigl' => [
            'titulo' => 'Recovery standard',
            'class' => 'ingles',
        ],
        'dscbbgdis' => [
            'titulo' => 'Bibliografia / References',
        ],

        'cgahoratvext' => [
            'titulo' => 'Carga horária (horas)',
            'ajuda' => 'Carga horária em atividade extensionista, definida em resolução 07/2018 do Conselho Nacional de Educação (CNE), que estabelece as diretrizes para a extensão na educação superior brasileira (instituições públicas e privadas) e que avaliações do Ministério da Educação (MEC) passam a considerar o currículo dos cursos com a extensão obrigatória.',
        ],
        'grpavoatvext' => [
            'titulo' => 'Grupo social alvo',
            'ajuda' => 'Descrever aqui as características do público com o qual os alunos desenvolverão as atividades',
        ],
        'grpavoatvextigl' => [
            'titulo' => 'Target social group',
            'ajuda' => 'Descrever aqui as características do público com o qual os alunos desenvolverão as atividades',
            'class' => 'ingles',
        ],
        'objatvext' => [
            'titulo' => 'Objetivos',
            'ajuda' => 'Detalhar aqui os resultados esperados com a realização da atividade extensionista, tanto para a formação dos discentes, quanto para o grupo social alvo da ação',
        ],
        'objatvextigl' => [
            'titulo' => 'Objectives',
            'ajuda' => 'Detalhar aqui os resultados esperados com a realização da atividade extensionista, tanto para a formação dos discentes, quanto para o grupo social alvo da ação',
            'class' => 'ingles',
        ],
        'dscatvext' => [
            'titulo' => 'Descrição',
            'ajuda' => 'Relatar aqui o resumo das ações a serem desenvolvidas pelos alunos',
        ],
        'dscatvextigl' => [
            'titulo' => 'Description',
            'ajuda' => 'Relatar aqui o resumo das ações a serem desenvolvidas pelos alunos',
            'class' => 'ingles',
        ],
        'idcavlatvext' => [
            'titulo' => 'Indicadores de avaliação da atividade',
            'ajuda' => 'Sinalizar aqui como o grupo social externo à Universidade poderá avaliar a atividade realizada conjuntamente com os estudantes, durante sua realização e ao final',
        ],
        'idcavlatvextigl' => [
            'titulo' => 'Activity evaluation indicators',
            'ajuda' => 'Sinalizar aqui como o grupo social externo à Universidade poderá avaliar a atividade realizada conjuntamente com os estudantes, durante sua realização e ao final',
            'class' => 'ingles',
        ],
    ];

    // tipdis
    public static $tipdis = [
        'S' => 'Semestral',
        'A' => 'Anual',
        'Q' => 'Quadrimestral',
    ];

    /**
     * Retorna dados replicados da diciplina, incluindo responsaveis, e cursos
     *
     * @param String $coddis
     */
    public static function obterDisciplinaReplicado($coddis)
    {
        $dr = Graduacao::obterDisciplina($coddis, 'max');
        if ($dr) {
            $dr['responsaveis'] = Graduacao::listarResponsaveisDisciplina($coddis);

            // vamos transformar estes campos em objeto de data
            foreach (['dtaatvdis', 'dtadtvdis'] as $campo) {
                $dr[$campo] = $dr[$campo] ? Carbon::parse($dr[$campo]) : null;
            }

            $dr['cursos'] = Graduacao::listarCursosDisciplina($coddis);
            return $dr;
        }
        return [];
    }

    /**
     * Similar a createOrNew, mas usando $coddis como chave
     */
    public static function primeiroOuNovo($coddis = null)
    {
        if ($coddis) {
            $dr = self::obterDisciplinaReplicado($coddis);
            $disc = self::where('coddis', $coddis)->first();
            if (!$disc) {
                $disc = self::novo($dr);
                $disc->estado = 'editar';
            }
            $disc->dr = $dr;
        } else {
            $disc = self::novo();
            $disc->estado = 'criar';
        }
        return $disc;
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
        foreach ($dr['responsaveis'] as $r) {
            if (strpos(json_encode($this->responsaveis), $r['codpes']) !== false) {
                // está no replicado e está no novo => mesmo
                $resps[] = ['codpes' => $r['codpes'], 'nompesttd' => $r['nompesttd'], 'status' => 'mesmo'];
            } else {
                // esta no replicado mas não está no novo => removido
                $resps[] = ['codpes' => $r['codpes'], 'nompesttd' => $r['nompesttd'], 'status' => 'removido'];
            }
        }

        foreach ($this->responsaveis as $r) {
            if (strpos(json_encode($dr['responsaveis']), $r['codpes']) !== false) {
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

        $discsLocal = self::where('responsaveis', 'like', '%' . $codpes . '%')->get();
        $discs = self::limparDisciplinasReplicado($discs, $discsLocal);

        return $discs;
    }

    /**
     * Lista todas as disciplinas da unidade - visão CG
     *
     * Guarda em cache do Laravel por 24h pois é uma consulta demorada.
     * O cache é renovado quando há alteração da diciplina no sistema passando $refresh = true
     */
    public static function listarDisciplinas($refresh = false)
    {
        if ($refresh) {
            Cache::forget('listarDisciplinas');
        }

        $discs = Cache::remember('listarDisciplinas', 60 * 60 * 24, function () {
            $drs = Graduacao::listarDisciplinas();
            $discs = self::mergearResponsaveis(collect(), $drs);

            $discsLocal = self::all();
            $discs = self::limparDisciplinasReplicado($discs, $discsLocal);

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
     * @return Illuminate\Support\Collection
     */
    public static function listarDisciplinasPorPrefixo($prefixo)
    {
        $drs = Graduacao::listarDisciplinasPorPrefixo($prefixo);
        $discs = self::mergearResponsaveis(collect(), $drs);

        $discsLocal = self::where('coddis', 'like', $prefixo . '%')->get();
        $discs = self::limparDisciplinasReplicado($discs, $discsLocal);

        return $discs;
    }

    /**
     * Retorna os prefixos das disciplinas da Unidade
     * 
     * Corresponde aos 3 primeiros dígitos de coddis
     */
    public static function listarPrefixosDisciplinas()
    {
        $discs = self::listarDisciplinas();
        $prefixos = [];
        foreach ($discs as $disc) {
            $p = substr($disc['coddis'], 0, 3);
            $prefixos[$p] = 0;
        }
        $prefixos = array_keys($prefixos);
        sort($prefixos);
        return $prefixos;
    }

    /**
     * Pega array de disciplinas do replicado, transforma em objeto e mescla responsaveis
     */
    protected static function mergearResponsaveis($discs, $drs)
    {
        foreach ($drs as $k => $dr) {
            $disc = new Disciplina();
            $disc->fill($dr);
            $disc->responsaveis = Graduacao::listarResponsaveisDisciplina($disc->coddis);
            $discs[] = $disc;
        }
        return $discs;
    }

    // protected static function mergearPrefixos($discs, $drs)
    // {
    //     foreach ($drs as $k => $dr) {
    //         $disc = new Disciplina();
    //         $disc->fill($dr);
    //         $disc->responsaveis = Graduacao::listarResponsaveisDisciplina($disc->coddis);
    //         $discs[] = $disc;
    //     }
    //     return $discs;
    // }
    
    /**
     * Limpa de $discs as disciplinas repetidas em $discsLocal
     */
    protected static function limparDisciplinasReplicado($discs, $discsLocal)
    {
        foreach ($discsLocal as $discLocal) {
            if ($discs->where('coddis', $discLocal->coddis) != false) {
                // tem no replicado e no BD local => vamos remover do arr do replicado
                $discs = $discs->where('coddis', '!=', $discLocal->coddis);
            }
            $discs[] = $discLocal;
        }
        return $discs;
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
    public function retornarListaResponsaveis($dr = false)
    {
        $ret = '';
        $src = $dr ? $this->dr['responsaveis'] : $this->responsaveis;
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
        return $dr ? self::$tipdis[$this->dr['tipdis']] : self::$tipdis[$this->tipdis];
    }

    /**
     * Relacionamento com user:criado por
     */
    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'criado_por_id');
    }

    public function atualizadoPor()
    {
        return $this->belongsTo(User::class, 'atualizado_por_id');
    }
}
