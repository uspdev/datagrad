<?php

$menu = [
    [
        'text' => 'Cursos',
        'url' => 'graduacao/cursos',
        'can' => 'disciplinas',
    ],
    [
        'text' => 'Validação de curso',
        'submenu' => [
            [
                'text' => 'Relatório síntese',
                'url'  => 'graduacao/relatorio/sintese',
                'can'  => 'datagrad',
            ],
            [
                'text' => 'Relatório complementar',
                'url'  => 'graduacao/relatorio/complementar',
                'can'  => 'datagrad',
            ],
        ],
        'can'  => 'datagrad',
    ],
    [
        'text' => 'Relatórios cursos',
        'submenu' => [
            [
                'text' => 'Relatório carga horária acumulada',
                'url'  => 'graduacao/relatorio/carga-acumulada',
                'can'  => 'relatorio-cgahoralu',
            ],
            [
                'text' => 'Quadro horários de aula',
                'url'  => 'graduacao/horarios',
                'can'  => 'relatorio-curso',
            ],
        ],
        'can'  => 'relatorio-curso',
    ],
    [
        'text' => 'Relatórios alunos',
        'submenu' => [
            [
                'text' => 'Relatório grade horária',
                'url'  => 'graduacao/relatorio/gradehoraria',
                'can'  => 'datagrad',
            ],
        ],
        'can'  => 'datagrad',
    ],
    [
        'text' => 'Estatísticas',
        'submenu' => [
            [
                'text' => 'Relatório de evasão',
                'url'  => 'graduacao/relatorio/evasao',
                'can'  => 'evasao',
            ],
            [
                'text' => 'Relatório de turmas',
                'url'  => 'graduacao/relatorio/turma',
                'can'  => 'datagrad',
            ],
        ],
        'can'  => 'datagrad',
    ],
    [
        'text' => 'Disciplinas',
        'url'  => 'disciplinas',
        'can'  => 'disciplinas',
    ],
    // [
    //     'text' => 'Relatório carga didática',
    //     'url' => 'graduacao/relatorio/cargadidatica',
    //     'can' => 'datagrad',
    // ],
];

$right_menu = [
    [
        'text' => '<span class="text-danger"><i class="fas fa-user-tag"></i> Funções</span>',
        'url' => 'roles',
        'can' => 'roles',
    ],
    [
        // menu utilizado para views da biblioteca senhaunica-socialite.
        'key' => 'senhaunica-socialite',
    ],
    [
        'key' => 'laravel-tools',
    ],
];

return [
    # valor default para a tag title, dentro da section title.
    # valor pode ser substituido pela aplicação.
    'title' => config('app.name'),

    # USP_THEME_SKIN deve ser colocado no .env da aplicação
    'skin' => env('USP_THEME_SKIN', 'uspdev'),

    # chave da sessão. Troque em caso de colisão com outra variável de sessão.
    'session_key' => 'laravel-usp-theme',

    # usado na tag base, permite usar caminhos relativos nos menus e demais elementos html
    # na versão 1 era dashboard_url
    'app_url' => config('app.url'),

    # login e logout
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',

    # menus
    'menu' => $menu,
    'right_menu' => $right_menu,

    # mensagens flash - https://uspdev.github.io/laravel#31-mensagens-flash
    'mensagensFlash' => false,
];
