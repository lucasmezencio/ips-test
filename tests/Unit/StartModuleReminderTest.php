<?php

namespace Tests\Unit;

use ArgumentCountError;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use iPSDevTestSeeder;
use Mockery;
use Tests\TestCase;

use App\{Module, User};

use App\Collections\ModuleCollection;
use App\Helpers\StartModuleReminderHelper;
use App\Services\InfusionsoftService;

/**
 * Class StartModuleReminderTest
 *
 * @package Tests\Unit
 */
class StartModuleReminderTest extends TestCase
{
    use RefreshDatabase;

    public function assertPreConditions()
    {
        $className = StartModuleReminderHelper::class;
        $this->assertTrue(class_exists($className), "Class not found: {$className}");
    }

    public function setUp()
    {
        parent::setUp();

        $this->seed(iPSDevTestSeeder::class);
    }

    /**
     * @test
     *
     * @expectedException ArgumentCountError
     */
    public function itShouldReceiveUserAndProducts(): void
    {
        new StartModuleReminderHelper();
    }

    /** @test */
    public function ifNoModulesAreCompletedItShouldReturnTheNextModuleInOrder(): void
    {
        $courses = ['ipa', 'iea'];
        /** @var Collection|ModuleCollection $modules */
        $modules = Module::all();
        /** @var User $user */
        $user = factory(User::class)->create();
        $infusionsoftService = $this->getMock($courses);
        $contact = $infusionsoftService->getContact($user->email);
        $module = $modules->getFirstByCourseKey($courses[0]);

        $startModuleReminder = new StartModuleReminderHelper($user, $contact['_Products'], $modules);
        $nextModule = $startModuleReminder->getNextModule();

        $this->assertEquals($module->id, $nextModule->id);

        $user->completed_modules()->attach(Module::where('course_key', 'ipa')->limit(3)->get());
        $user->completed_modules()->attach(Module::where('name', 'IPA Module 5')->first());

        $nextModule = $startModuleReminder->getNextModule();

        $this->assertEquals(14, $nextModule->id);
    }

    /** @test */
    public function ifAllOrLastFirstCourseModulesAreCompletedItShouldReturnNull(): void
    {
        $courses = ['ipa', 'iea'];
        /** @var User $user */
        $user = factory(User::class)->create();
        $infusionsoftService = $this->getMock($courses);
        $contact = $infusionsoftService->getContact($user->email);
        /** @var Collection|ModuleCollection $modules */
        $modules = Module::all();

        $user->completed_modules()->attach($modules);

        $startModuleReminder = new StartModuleReminderHelper($user, $contact['_Products'], $modules);
        $nextModule = $startModuleReminder->getNextModule();

        $this->assertNull($nextModule);

        $user->completed_modules()->detach();

        $lastModules = $modules->getLastModulePerCourse();

        $user->completed_modules()->attach($lastModules);

        $nextModule = $startModuleReminder->getNextModule();

        $this->assertNull($nextModule);
    }

    public function getMock(array $products)
    {
        $mock = Mockery::mock(InfusionsoftService::class);
        $mock->shouldReceive('getContact')
            ->andReturn([
                'Email' => 'lucas@test.com',
                '_Products' => implode(',', $products),
                'Id' => 123,
            ]);

        return $mock;
    }
}
