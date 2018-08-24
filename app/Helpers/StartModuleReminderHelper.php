<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;

use App\{Module, User};

use App\Collections\ModuleCollection;

class StartModuleReminderHelper
{
    /** @var User */
    private $user;
    /** @var array */
    private $products;
    /** @var Collection|ModuleCollection */
    private $modules;

    /**
     * StartModuleReminderHelper constructor.
     *
     * @param User $user
     * @param string $products
     * @param Collection $modules
     */
    public function __construct(User $user, string $products, Collection $modules)
    {
        $this->user = $user;
        $this->products = explode(',', $products);
        $this->modules = $modules;
    }

    /**
     * @return Module|null
     */
    public function getNextModule(): ?Module
    {
        $firstCourse = $this->products[0];

        // No modules completed
        if (!$this->user->completed_modules()->count()) {
            return $this->modules->getFirstByCourseKey($firstCourse);
        }

        // All modules completed
        if ($this->user->completed_modules()->count() === $this->modules->count()) {
            return null;
        }

        // All last modules from all courses completed
        if ($this->userHasCompletedAllCourses()) {
            return null;
        }

        // Next uncompleted module after the last completed module
        $lastCompletedModule = $this->getLastCompletedModule();

        if ($lastCompletedModule !== null) {
            return $this->modules->where('id', $lastCompletedModule->id + 1)->first();
        }

        return null;
    }

    /**
     * @return bool
     */
    private function userHasCompletedAllCourses(): bool
    {
        /** @var Collection $completedModulesIds */
        $completedModulesIds = $this->user->completed_modules()->get()->pluck('id');
        /** @var Collection $lastModulesPerCourse */
        $lastModulesPerCourse = $this->modules->getLastModulePerCourse()->pluck('id');

        return $lastModulesPerCourse->intersect($completedModulesIds)->count() === $lastModulesPerCourse->count();
    }

    /**
     * @return mixed|null
     */
    private function getLastCompletedModule()
    {
        $lastModuleCompleted = null;

        foreach ($this->products as $userProduct) {
            /** @var Collection $userModules */
            $userModules = $this->modules
                ->where('course_key', $userProduct)
                ->whereIn('id', $this->user->completed_modules()->get()->pluck('id'));

            if ($userModules->count()) {
                $lastModuleCompleted = $userModules->last();
            }
        }

        return $lastModuleCompleted;
    }
}
