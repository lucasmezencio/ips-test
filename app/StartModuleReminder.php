<?php

namespace App;

use Illuminate\Database\Eloquent\{Collection, Model};

use App\Collections\StartModuleReminderCollection;

/**
 * Class StartModuleReminder
 *
 * @package App
 */
class StartModuleReminder extends Model
{
    /**
     * @param array $models
     *
     * @return StartModuleReminderCollection|Collection
     */
    public function newCollection(array $models = [])
    {
        return new StartModuleReminderCollection($models);
    }
}
