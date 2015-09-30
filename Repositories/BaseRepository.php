<?php
/**
 * Created by PhpStorm.
 * User: Evgeni
 * Date: 30.09.2015 ã.
 * Time: 09:36 ÷.
 */

namespace Framework\Repositories;

use Framework\Models\BaseModel;

abstract class BaseRepository
{
    abstract public function remove($id);

    abstract public function getOne($id);

    abstract public function getAll();

    abstract public function save($obj);
}