<?php

namespace Framework\Repositories;


use Framework\Config\ApplicationConfig;
use Framework\Core\Database;
use Framework\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * @var \Framework\Core\Database
     */
    private $db;

    /**
     * @var UserRepository
     */
    private static $inst;

    public function __construct(\Framework\Core\Database $db) {
        $this->db = $db;
    }

    /**
     * @return UserRepository
     * @throws \Exception
     */
    public static function create() {
        if(self::$inst == null) {
            self::$inst = new self(Database::getIntance(ApplicationConfig::DB_INSTANCE));
        }

        return self::$inst;
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
    }

    /**
     * @param $id
     */
    public function getOne($id)
    {
        $query = '
            SELECT id, username, password
            FROM users
            WHERE id = ?
        ';

        $result = $this->db->prepare($query);
        $result->execute([$id]);

        if ($result->rowCount() == 0) {
            throw new \Exception('Invalid userid');
        }

        $userRow = $result->fetch();

        return new User(
            $userRow['username'],
            $userRow['password'],
            $userRow['id']
        );
    }

    public function getOneByDetails($username, $password) {
        $query = '
            SELECT id, username, password
            FROM users
            WHERE username = ?
        ';

        $result = $this->db->prepare($query);
        $result->execute([$username]);

        $user = $result->fetch();

        if (empty($user)) {
            throw new \Exception('User with this username does not exists.');
        }

        if(password_verify($password, PASSWORD_DEFAULT)) {
            throw new \Exception('Wrong password');
        }
        return $this->getOne($user['id']);
    }

    public function getAll()
    {
        $query = '
            SELECT id, username, password
            FROM users
        ';

        $result = $this->db->prepare($query);
        $result->execute();

        if ($result->rowCount() == 0) {
            throw new \Exception('No users found');
        }

        $users = $result->fetchAll();

        array_map(function($item) {
            return new User(
                $item['username'],
                $item['password'],
                $item['id']
            );
        }, $users);

        return $users;
    }

    public function save($user) {
        var_dump($user);
        $query = "
            INSERT INTO users (username, password)
            VALUES (?, ?)
        ";
        $params = [
            $user->getUsername(),
            $user->getPassword()
        ];
        if ($user->getId()) {
            $query = "UPDATE users SET username = ?, password = ? WHERE id = ?";
            $params[] = $user->getId();
        }
        $result = $this->db->prepare($query);
        $result->execute($params);

        return $result->rowCount() > 0;
    }

    public function exists($username) {

        $result = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $result->execute([$username]);

        return $result->rowCount() > 0;
    }

    public function register($username, $password) {
        if($this->exists($username)) {
            throw new \Exception("User already registered");
        }

        $result = $this->db->prepare('
            INSERT INTO users (username, password)
            VALUES (?, ?)
        ');

        $result->execute(
            [
                $username,
                password_hash($password, PASSWORD_DEFAULT)
            ]
        );

        if($result->rowCount() > 0) {
            return true;
        }

        throw new \Exception('Cannot register user');
    }

    public function login($username, $password) {

        $result = $this->db->prepare("
            SELECT * FROM users
            WHERE username = ?
        ");

        $result->execute([$username]);

        if ($result->rowCount() == 0) {
            throw new \Exception('Invalid username');
        }

        $userRow = $result->fetch();

        if(password_verify($password, $userRow['password'])) {
            return $userRow['id'];
        } else {
            throw new \Exception('Wrong password');
        }
    }

    public function getInfo($id) {
        $result = $this->db->prepare("
            SELECT
                id, username, password
            FROM
                users
            WHERE
                id = ?
        ");

        $result->execute([$id]);

        return $result->fetch();
    }

    public function edit(User $user) {
        $result = $this->db->prepare('UPDATE users SET password = ?, username = ? WHERE id = ?');
        $result->execute([
            $user->getPassword(),
            $user->getUsername(),
            $user->getId()
        ]);

        return $result->rowCount() > 0;
    }
}