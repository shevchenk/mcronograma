<?php namespace Chat\Repositories\User;

use Chat\Repositories\DbRepository;

class DbUserRepository extends DbRepository implements UserRepository {

    /**
     * @var Usuario
     */
    private $model;

    public function __construct(\Usuario $model)
    {
        $this->model = $model;
    }

    public function getAllExcept($id)
    {
        return $this->model->where('id', '<>', $id)->get();
    }
}
