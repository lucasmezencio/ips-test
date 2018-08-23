<?php

namespace App\Console\Commands;

use App\Services\InfusionsoftService;
use App\StartModuleReminder;
use Illuminate\Console\Command;

class PopulateReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ips:populate_reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var InfusionsoftService
     */
    private $infusionsoftService;

    /**
     * Create a new command instance.
     *
     * @param InfusionsoftService $infusionsoftService
     */
    public function __construct(InfusionsoftService $infusionsoftService)
    {
        $this->infusionsoftService = $infusionsoftService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tags = $this->infusionsoftService->getAllTags();

        foreach (json_decode($tags) as $tag) {
            $startModuleReminder = new StartModuleReminder();
            $startModuleReminder->original_id = $tag->id;
            $startModuleReminder->name = $tag->name;
            $startModuleReminder->description = $tag->description;
            $startModuleReminder->category = $tag->category;
            $startModuleReminder->save();
        }

        $this->comment('Done!');
    }
}
