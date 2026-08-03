<?php

namespace App\Extensions;

use DateTimeInterface;
use Illuminate\Database\PostgresConnection;
use PDO;
use PDOStatement;

/**
 * @source https://github.com/vermaysha/pgbouncer-laravel-extension
 */
class PgBouncerExtension extends PostgresConnection
{
    /**
     * @param  PDOStatement  $statement
     * @param  array  $bindings
     */
    public function bindValues($statement, $bindings): void
    {
        foreach ($bindings as $key => $value) {
            $statement->bindValue(
                is_string($key) ? $key : $key + 1,
                $value,
                match (true) {
                    is_int($value) => PDO::PARAM_INT,
                    is_resource($value) => PDO::PARAM_LOB,
                    $value === null => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR
                },
            );
        }
    }

    /**
     * @param  array  $bindings
     */
    public function prepareBindings($bindings): array
    {
        $grammar = $this->getQueryGrammar();

        foreach ($bindings as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                $bindings[$key] = $value->format($grammar->getDateFormat());
            } elseif (is_bool($value)) {
                $bindings[$key] = $value ? 'true' : 'false';
            } elseif (is_array($value)) {
                $bindings[$key] = json_encode($value);
            } elseif (is_object($value) && method_exists($value, '__toString')) {
                $bindings[$key] = (string) $value;
            } elseif (is_object($value)) {
                $bindings[$key] = json_encode($value);
            }
        }

        return $bindings;
    }
}
