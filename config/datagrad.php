<?php

return [

    'evasaoCodcurIgnorados' => explode(',', env('EVASAO_CODCUR_IGNORADOS', null)),
    'evasaoCodcurHabIgnorados' => explode(',', env('EVASAO_CODCUR_HAB_IGNORADOS', null)),
    'codhabs' => explode(',', env('CODHABS', 0)),
];
