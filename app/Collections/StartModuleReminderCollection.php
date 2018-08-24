<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class StartModuleReminderCollection
 *
 * @package App\Collections
 */
class StartModuleReminderCollection extends Collection
{
    /**
     * @param string $moduleName
     *
     * @return mixed
     */
    public function getTagByModuleName(string $moduleName)
    {
        return $this->filter(function ($tag) use ($moduleName) {
            return stripos($tag->name, $moduleName) !== false;
        })->first();
    }
}
