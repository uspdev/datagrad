<?php

$menu = [
    [
        'text' => 'Cursos',
        'url' => 'graduacao/cursos',
        'can' => 'datagrad',
    ],
    [
        'text' => 'Relatório síntese',
        'url' => 'graduacao/relatorio/sintese',
        'can' => 'datagrad',
    ],
    [
        'text' => 'Relatório complementar',
        'url' => 'graduacao/relatorio/complementar',
        'can' => 'datagrad',
    ],
    [
        'text' => 'Relatório carga didática',
        'url' => 'graduacao/relatorio/cargadidatica',
        'can' => 'datagrad',
    ],
    [
        'text' => 'Relatório grade horária',
        'url' => 'graduacao/relatorio/gradehoraria',
        'can' => 'datagrad',
    ],
    [
        'text' => 'Relatório de evasão',
        'url' => 'graduacao/relatorio/evasao',
        'can' => 'evasao',
    ],
    [
        'text' => 'Disciplinas',
        'url' => 'disciplinas',
        'can' => 'datagrad',
    ],
];

$right_menu = [
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
    'mensagensFlash' => true,
];
