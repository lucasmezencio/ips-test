<?php

namespace App;

use Illuminate\Database\Eloquent\{Collection, Model};

use App\Collections\ModuleCollection;

/**
 * Class Module
 *
 * @package App
 */
class Module extends Model
{
    /**
     * @param array $models
     *
     * @return ModuleCollection|Collection
     */
    public function newCollection(array $models = [])
    {
        return new ModuleCollection($models);
    }
}
