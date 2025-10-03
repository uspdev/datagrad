<?php

namespace App\Models;

use App\Replicado\Pessoa;
use App\Casts\AlwaysArray;
use App\Casts\BrazilianDate;
use App\Replicado\Graduacao;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public static $estados = [
        'Criar',
        'Propor alteração',
        'Em edição',
        'Em aprovação',
        'Aprovado',
        'Finalizado'
    ];

    /** Dados que vem do replicado */
    public $dr;

    /** Cursos da unidade para os quais a disciplina é ministrada */
    public $cursos;

    /** Dados dos campos do formulário de alteração de disciplina */
    public $meta = [
        'tipdis' => [
            'titulo' => 'Tipo/Type',
            'diff' => false,
            'options' => [
                'S' => 'Semestral',
                'A' => 'Anual',
                'Q' => 'Quadrimestral',
            ],
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
        'codlinegr' => [
            'titulo' => 'Idioma/Language',
            'options' => [
                'POR' => 'Português',
                'ENG' => 'Inglês',
            ],
        ],
        'nomdis' => [
            'titulo' => 'Nome',
        ],
        'nomdisigl' => [
            'titulo' => 'Name',
            'class' => 'ingles',
        ],
        'pgmrsudis' => [
            'titulo' => 'Ementa (sinopse da disciplina)',
            'ajuda' => 'Trata da apresentação da disciplina, em linhas gerais; da descrição de sua proposta.
            (Associar o conteúdo resumido aos objetivos de aprendizagem com a bibliografia de referência.)',
        ],
        'pgmrsudisigl' => [
            'titulo' => 'Course Description',
            'class' => 'ingles',
        ],
        'objdis' => [
            'titulo' => 'Objetivos',
            'ajuda' => 'O que se quer alcançar com a disciplina, em termos de aprendizagem dos estudantes;
             ou seja, expressa o que se espera que os estudantes aprendam ao final da disciplina, 
             em determinadas condições de ensino, tendo em vista que a ocorrência dessa aprendizagem possa ser verificada.',
        ],
        'objdisigl' => [
            'titulo' => 'Objectives',
            'class' => 'ingles',
        ],
        'pgmdis' => [
            'titulo' => 'Conteúdo Programático',
            'ajuda' => 'Indica os conteúdos que permitirão o alcance dos objetivos definidos.
             Nesse sentido, os conteúdos de ensino são meios para a realização dos objetivos, 
             e não os objetivos em si. Conteúdos são conhecimentos que se considera essencial 
             que sejam apreendidos e reelaborados pelos estudantes para que os objetivos sejam alcançados. 
             Conteúdo é o conjunto de conhecimentos, habilidades, atitudes e valores que serão 
             desenvolvidos ao longo da disciplina.',
        ],
        'pgmdisigl' => [
            'titulo' => 'Full Program',
            'class' => 'ingles',
        ],
        'mtdens' => [
            // otitulo combina com inglês pois será apenas um checkbox em pt-br
            'titulo' => 'Métodos de Ensino',
            'ajuda' => 'Descreve como os conteúdos serão desenvolvidos para que os objetivos possam ser alcançados: 
            considerar tempos, espaços e recursos que possam contribuir para a aprendizagem. 
            São os procedimentos de ensino, as ações e atividades que serão propostas ao longo da disciplina, 
            em função dos objetivos previstos. Apresenta as estratégias de ensino que orientarão a prática educativa',
            'options' => [
                'Aula expositiva',
                'Aula dialogada',
                'Atividades em laboratório',
                'Simulações',
                'Seminários',
                'Estudo coletivo com ou sem apresentação dos resultados',
                'Construção de Portfólio',
                'Roda de conversa',
                'Tempestade cerebral',
                'Mapa conceitual',
                'Estudo dirigido',
                'Aulas orientadas',
                'Lista de discussão por meios informatizados',
                'Estudo de caso: situação-problema, filmes, imagens, frases, expressões, notícias, entrevistas, depoimentos, documentos.',
                'Atividade para discussão e solução de problemas',
                'Resolução de exercícios',
                'Ensino em pequenos grupos',
                'Trabalho em grupo / grupos de trabalho',
                'Dramatização de situação',
                'Seminário, Simpósio, Palestras - com a turma ou sua organização conjunta',
                'Painel',
                'Entrevistas',
                'Aula com Convidados',
                'Fórum de Discussão e debates',
                'Oficina ou Laboratório de construção e testagem',
                'Estudo do meio',
                'Ensino com pesquisa',
                'Exposições e visitas com produção de relatório',
                'Ensino individualizado',
                'Diários de aprendizagem',
                'Ensino baseado em projetos, em problemas',
            ],
        ],
        'mtdensigl' => [
            'titulo' => 'Teaching Methods',
            'class' => 'ingles',
            'options' => [
                'Lecture',
                'Dialogued class',
                'Laboratory activities',
                'Simulations',
                'Seminars',
                'Collective study with or without presentation of results',
                'Portfolio development',
                'Roundtable discussion',
                'Brainstorming',
                'Concept map',
                'Guided study',
                'Tutored classes',
                'Discussion list via digital media',
                'Case study: problem-situation, films, images, sentences, expressions, news, interviews, testimonials, documents.',
                'Activity for discussion and problem solving',
                'Exercise solving',
                'Small group teaching',
                'Group work / working groups',
                'Role play',
                'Seminar, Symposium, Lectures - with the class or jointly organized',
                'Panel',
                'Interviews',
                'Guest lecture',
                'Discussion forum and debates',
                'Workshop or construction and testing lab',
                'Field study',
                'Research-based teaching',
                'Exhibitions and visits with report production',
                'Individualized teaching',
                'Learning journals',
                'Project-based and problem-based learning',
            ],

        ],
        'dscmtdavl' => [
            'titulo' => 'Método de Avaliação',
            'ajuda' => 'Descrever com clareza o processo de avaliação de aprendizagem para que o aluno compreenda 
            como todos os elementos do plano de ensino se articulam e para que o professor possa realizar a gestão 
            da aprendizagem na sua disciplina, com base em evidências do que o aluno aprendeu, 
            as lacunas na aprendizagem e o que precisa ser redimensionado em termos de ensino. 
            O estudante precisa também ter clareza do que o professor espera dele em relação à aprendizagem e, 
            portanto, o que será avaliado, a partir do que foi ensinado. A avaliação, portanto, deve gerar informações 
            sobre o processo de ensino e de aprendizagem para que o próprio processo possa ser replanejado, 
            se necessário. O feedback da aprendizagem para o estudante, durante o processo, é fundamental 
            para que ele esteja seguro de onde precisa avançar e como, por isso, ele deve ser disponibilizado a tempo.',
        ],
        'dscmtdavligl' => [
            'titulo' => 'Evaluation Method',
            'class' => 'ingles',
        ],
        'crtavl' => [
            'titulo' => 'Critérios de Aprovação',
            'ajuda' => 'A escolha do instrumento de avaliação deve levar em conta aquele que poderá fornecer 
            o maior conjunto de informações sobre a aprendizagem dos estudantes, para que o professor possa 
            tomar decisões seguras e precisas sobre como intervir para potencializar a aprendizagem e intervir 
            onde haja necessidade, para tornar a aprendizagem mais efetiva para todos.',
        ],
        'crtavligl' => [
            'titulo' => 'Evaluation Criterion',
            'class' => 'ingles',
        ],
        'dscnorrcp' => [
            'titulo' => 'Norma de recuperação (da aprendizagem)',
            'ajuda' => 'Nesse caso, o foco é a recuperação da aprendizagem. Para isso, o estudante deve saber onde 
            precisa melhorar, em termos de aprendizagem, e como, quais recursos adicionais ele poderá buscar 
            para aprender o que não conseguiu ainda.'
        ],
        'dscnorrcpigl' => [
            'titulo' => 'Recovery standard',
            'class' => 'ingles',
        ],
        'dscbbgdis' => [
            'titulo' => 'Bibliografia Básica / Bibliography',
        ],
        'dscbbgdiscpl' => [
            'titulo' => 'Bibliografia Complementar',
        ],
        'objdslsut' => [ // 17 ODS da ONU
            'titulo' => 'Objetivos de Desenvolvimento Sustentável',
            'options' => [
                'Erradicação da pobreza',
                'Fome zero e agricultura sustentável',
                'Saúde e bem-estar',
                'Educação de qualidade',
                'Igualdade de gênero',
                'Água potável e saneamento',
                'Energia limpa e acessível',
                'Trabalho decente e crescimento econômico',
                'Indústria, inovação e infraestrutura',
                'Redução das desigualdades',
                'Cidades e comunidades sustentáveis',
                'Consumo e produção responsáveis',
                'Ação contra a mudança global do clima',
                'Vida na água',
                'Vida terrestre',
                'Paz, justiça e instituições eficazes',
                'Parcerias e meios de implantação',
            ],
        ],
        'stavgmdid' => [
            'titulo' => 'Viagem didática?',
        ],
        'staetr' => [
            'titulo' => 'É estruturante?',
        ],
        'dscatvpvs' => [
            'titulo' => 'Descrição das atividades previstas',
        ],

        // atividades animais
        'stapsuatvani' => [
            'titulo' => 'Atividades práticas com animais e/ou materiais biológicos?',
        ],
        'ptccmseiaani' => [
            'titulo' => 'Protocolo CEUA',
            'ajuda' => 'Número do protocolo emitido pela CEUA - Comissão de Ética de Uso de Animais',
        ],
        'dtainivalprp' => [
            'titulo' => 'Data início da validade da proposta',
            'ajuda' => 'Data início da validade da proposta de manuseio de animais/matérias biológicos encaminhada para comissão CEUA',
        ],
        'dtafimvalprp' => [
            'titulo' => 'Data fim da validade da proposta',
            'ajuda' => 'Data fim da validade da proposta de manuseio de animais/matérias biológicos encaminhada para comissão CEUA',
        ],

        // atividades extensionistas
        'atividade_extensionista' => [
            'titulo' => 'Atividade extensionista',
            'diff' => false,
            'options' => [
                true => 'Sim',
                false => 'Não',
            ],
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

        // alterar disciplina existente
        $disc = self::where('coddis', $coddis)->where('estado', '!=', 'Finalizado')->first();

        $verdis = $disc ? $disc->verdis : 'max';
        $dr = self::obterDisciplinaReplicado($coddis, $verdis);

        if (!$disc) {
            $disc = self::novo($dr);
            $disc->estado = 'Propor alteração';
        }

        $disc->dr = $dr;

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
     * Guarda em cache do Laravel por 12h pois é uma consulta demorada.
     * O cache é renovado quando há alteração da diciplina no sistema passando $refresh = true
     */
    public static function listarDisciplinas($refresh = false)
    {
        if ($refresh) {
            Cache::forget('listarDisciplinas');
        }

        $discs = Cache::remember('listarDisciplinas', 60 * 60 * 12, function () {
            $drs = Graduacao::listarDisciplinas();
            $discs = self::mergearResponsaveis(collect(), $drs);

            $discsLocal = self::where('estado', '!=', 'Finalizado')->get();
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

        // vamos pegar as disciplinas locais do prefixo
        $discsLocal = self::where('coddis', 'like', $prefixo . '%')->where('estado', '!=','Finalizado')->get();

        // vamos limpar repetidos
        $discs = self::limparDisciplinasReplicado($discs, $discsLocal);

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
            $disc->dr = $dr;
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
     * Retorna se a disciplina está em um estado editável ou não
     * 
     * @return boolean
     */
    public function isEditavel()
    {
        return $this->estado == 'Em edição' || $this->estado == 'Propor alteração';
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
        $todasEn = $ingles && isset($curso->{$colCursoEn})
            ? preg_split('/\r\n|\r|\n/', trim($curso->{$colCursoEn}))
            : [];

        // Itens escolhidos pela disciplina
        $escolhidas = $this->{$colDisc}[$codcur] ?? [];

        $resultado = [];
        foreach ($todasPt as $i => $itemPt) {
            if (in_array($itemPt, $escolhidas)) {
                $valor = $ingles ? ($todasEn[$i] ?? '') : $itemPt;
                if (!empty(trim($valor))) {
                    $resultado[] = $prefix . ($i + 1) . '. ' . $valor . ';';
                }
            }
        }

        return $resultado;
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
