<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): ?Model;

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;
}
