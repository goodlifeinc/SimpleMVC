<?php

namespace Framework\Core\Drivers;


class DriverFactory
{
    public static function create($driver, $user, $pass, $dbName, $host) {
        return new MySQLDriver($user, $pass, $dbName, $host);
    }
}