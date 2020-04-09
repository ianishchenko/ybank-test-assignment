<?php

namespace App\Repository\Eloquent;

use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package App\Repository\Eloquent
 */
abstract class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }
}
