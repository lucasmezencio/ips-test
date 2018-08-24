<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class ModuleCollection
 *
 * @package App\Collections
 */
class ModuleCollection extends Collection
{
    /**
     * @param string $courseKey
     *
     * @return mixed
     */
    public function getFirstByCourseKey(string $courseKey)
    {
        return $this->where('course_key', $courseKey)->sortBy('name')->first();
    }

    /**
     * @return ModuleCollection
     */
    public function getLastModulePerCourse(): ModuleCollection
    {
        return $this->filter(function ($module) {
            return $module->name === $this->where('course_key', $module->course_key)->max('name');
        });
    }
}
