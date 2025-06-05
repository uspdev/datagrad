<?php

return [
    'codundclgs' => explode(',', env('REPLICADO_CODUNDCLGS', null)),
    'evasaoCodcurIgnorados' => explode(',', env('EVASAO_CODCUR_IGNORADOS', null)),
    'evasaoCodcurHabIgnorados' => explode(',', env('EVASAO_CODCUR_HAB_IGNORADOS', null)),
];
