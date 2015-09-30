<?php

namespace Framework\Repositories;


class Repositories
{
    /**
     * @var \Framework\Repositories\UserRepository;
     */
    private static $userRepo;

    public function __construct() {
        self::$userRepo = UserRepository::create();
    }

    /**
     * @return UserRepository
     */
    public static function getUserRepo()
    {
        return self::$userRepo;
    }
}