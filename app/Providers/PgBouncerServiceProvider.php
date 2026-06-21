<?php

namespace App\Providers;

use App\Extensions\PgBouncerExtension;
use Illuminate\Database\Connection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Support\ServiceProvider;
use PDO;

/**
 * @source https://github.com/vermaysha/pgbouncer-laravel-extension
 */

class PgBouncerServiceProvider extends ServiceProvider
{ 
    public function register(): void
    {
        Connection::resolverFor('pgsql', function ($connection, $database, $prefix, $config) {
            $emulatePrepare = $config['options'][PDO::ATTR_EMULATE_PREPARES] ?? false;

            if ($emulatePrepare) {
                return new PgBouncerExtension($connection, $database, $prefix, $config);
            }

            return new PostgresConnection($connection, $database, $prefix, $config);
        });
    } 
}
