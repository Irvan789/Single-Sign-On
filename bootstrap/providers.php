<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\PgBouncerServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    PgBouncerServiceProvider::class,
];
