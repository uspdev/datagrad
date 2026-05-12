<?php
// 17 ODS da ONU
$ods = [
    1 => 'Erradicação da pobreza',
    2 => 'Fome zero e agricultura sustentável',
    3 => 'Saúde e bem-estar',
    4 => 'Educação de qualidade',
    5 => 'Igualdade de gênero',
    6 => 'Água potável e saneamento',
    7 => 'Energia limpa e acessível',
    8 => 'Trabalho decente e crescimento econômico',
    9 => 'Indústria, inovação e infraestrutura',
    10 => 'Redução das desigualdades',
    11 => 'Cidades e comunidades sustentáveis',
    12 => 'Consumo e produção responsáveis',
    13 => 'Ação contra a mudança global do clima',
    14 => 'Vida na água',
    15 => 'Vida terrestre',
    16 => 'Paz, justiça e instituições eficazes',
    17 => 'Parcerias e meios de implantação',
];

// metodologias de ensiono
$mtdens = [
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
];

// Dados dos campos do formulário de alteração de disciplina
$meta = [
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
        'rules' => 'required|numeric|min:1',
    ],
    'cretrb' => [
        'titulo' => 'Créditos-trabalho/Practice hour credits',
        'diff' => false,
    ],
    'numvagdis' => [
        'titulo' => 'Número de vagas/Number of places',
        'diff' => false,
        'rules' => 'required|numeric|min:1',
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
        'maxlength' => 240,
    ],
    'nomdisigl' => [
        'titulo' => 'Name',
        'class' => 'ingles',
        'maxlength' => 240,
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
        // o titulo combina com inglês pois será apenas um checkbox em pt-br
        'titulo' => 'Métodos de Ensino',
        'ajuda' => 'Descreve como os conteúdos serão desenvolvidos para que os objetivos possam ser alcançados:
                    considerar tempos, espaços e recursos que possam contribuir para a aprendizagem.
                    São os procedimentos de ensino, as ações e atividades que serão propostas ao longo da disciplina,
                    em função dos objetivos previstos. Apresenta as estratégias de ensino que orientarão a prática educativa',
        'options' => $mtdens,
    ],
    'mtdensigl' => [
        'titulo' => 'Teaching Methods',
        'class' => 'ingles',
        'options' => ['Lecture', 'Dialogued class', 'Laboratory activities', 'Simulations', 'Seminars', 'Collective study with or without presentation of results', 'Portfolio development', 'Roundtable discussion', 'Brainstorming', 'Concept map', 'Guided study', 'Tutored classes', 'Discussion list via digital media', 'Case study: problem-situation, films, images, sentences, expressions, news, interviews, testimonials, documents.', 'Activity for discussion and problem solving', 'Exercise solving', 'Small group teaching', 'Group work / working groups', 'Role play', 'Seminar, Symposium, Lectures - with the class or jointly organized', 'Panel', 'Interviews', 'Guest lecture', 'Discussion forum and debates', 'Workshop or construction and testing lab', 'Field study', 'Research-based teaching', 'Exhibitions and visits with report production', 'Individualized teaching', 'Learning journals', 'Project-based and problem-based learning'],
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
            para aprender o que não conseguiu ainda.',
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
    'objdslsut' => [
        'titulo' => 'Objetivos de Desenvolvimento Sustentável',
        'options' => $ods,
    ],

    // viagem didática
    'stavgmdid' => [
        'titulo' => 'Viagem didática?',
    ],
    'staetr' => [
        'titulo' => 'É estruturante?',
        'options' => [
            'Sim',
            'Não',
        ],
        'rules' => 'required_if:stavgmdid,S',
    ],
    'dscatvpvs' => [
        'titulo' => 'Descrição das atividades previstas',
        'rules' => 'required_if:stavgmdid,S',
    ],

    // atividades animais
    'stapsuatvani' => [
        'titulo' => 'Atividades práticas com animais e/ou materiais biológicos?',
    ],
    'ptccmseiaani' => [
        'titulo' => 'Protocolo CEUA',
        'ajuda' => 'Número do protocolo emitido pela CEUA - Comissão de Ética de Uso de Animais',
        'rules' => 'required_if:stapsuatvani,S',
    ],
    'dtainivalprp' => [
        'titulo' => 'Data início da validade da proposta',
        'ajuda' => 'Data início da validade da proposta de manuseio de animais/matérias biológicos encaminhada para comissão CEUA',
        'rules' => 'required_if:stapsuatvani,S',
    ],
    'dtafimvalprp' => [
        'titulo' => 'Data fim da validade da proposta',
        'ajuda' => 'Data fim da validade da proposta de manuseio de animais/matérias biológicos encaminhada para comissão CEUA',
        'rules' => 'required_if:stapsuatvani,S',
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
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'grpavoatvext' => [
        'titulo' => 'Grupo social alvo',
        'ajuda' => 'Descrever aqui as características do público com o qual os alunos desenvolverão as atividades',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'grpavoatvextigl' => [
        'titulo' => 'Target social group',
        'ajuda' => 'Descrever aqui as características do público com o qual os alunos desenvolverão as atividades',
        'class' => 'ingles',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'objatvext' => [
        'titulo' => 'Objetivos',
        'ajuda' => 'Detalhar aqui os resultados esperados com a realização da atividade extensionista, tanto para a formação dos discentes, quanto para o grupo social alvo da ação',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'objatvextigl' => [
        'titulo' => 'Objectives',
        'ajuda' => 'Detalhar aqui os resultados esperados com a realização da atividade extensionista, tanto para a formação dos discentes, quanto para o grupo social alvo da ação',
        'class' => 'ingles',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'dscatvext' => [
        'titulo' => 'Descrição',
        'ajuda' => 'Relatar aqui o resumo das ações a serem desenvolvidas pelos alunos',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'dscatvextigl' => [
        'titulo' => 'Description',
        'ajuda' => 'Relatar aqui o resumo das ações a serem desenvolvidas pelos alunos',
        'class' => 'ingles',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'idcavlatvext' => [
        'titulo' => 'Indicadores de avaliação da atividade',
        'ajuda' => 'Sinalizar aqui como o grupo social externo à Universidade poderá avaliar a atividade realizada conjuntamente com os estudantes, durante sua realização e ao final',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    'idcavlatvextigl' => [
        'titulo' => 'Activity evaluation indicators',
        'ajuda' => 'Sinalizar aqui como o grupo social externo à Universidade poderá avaliar a atividade realizada conjuntamente com os estudantes, durante sua realização e ao final',
        'class' => 'ingles',
        'rules' => 'required_if_accepted:atividade_extensionista',
    ],
    // justificativa
    'ano' => [
        'titulo' => 'Ano de criação',
    ],
    'semestre' => [
        'titulo' => 'Semestre de criação',
    ],
    'justificativa' => [
        'titulo' => 'Justificativa',
    ],

];

return [
    'codundclgs' => explode(',', env('REPLICADO_CODUNDCLGS', null)),
    'evasaoCodcurIgnorados' => explode(',', env('EVASAO_CODCUR_IGNORADOS', null)),
    'evasaoCodcurHabIgnorados' => explode(',', env('EVASAO_CODCUR_HAB_IGNORADOS', null)),
    'codhabs' => explode(',', env('CODHABS', 0)),

    // 'ods' => $ods,
    // 'mtdens' => $mtdens,
    'meta' => $meta,
];
