<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\{Collection, ModelNotFoundException};
use Illuminate\Http\JsonResponse;
use Response;

use App\{Module, User};

use App\Collections\StartModuleReminderCollection;
use App\Helpers\StartModuleReminderHelper;
use App\Services\InfusionsoftService;
use App\StartModuleReminder;

class ApiController extends Controller
{
    /** @var InfusionsoftService */
    private $infusionsoftService;

    /**
     * ApiController constructor.
     *
     * @param InfusionsoftService $infusionsoftService
     */
    public function __construct(InfusionsoftService $infusionsoftService)
    {
        $this->infusionsoftService = $infusionsoftService;
    }

    /**
     * @param string $email
     *
     * @return JsonResponse
     */
    public function moduleReminderAssignerAction(string $email): JsonResponse
    {
        $status = false;

        try {
            $tags = StartModuleReminder::all();
            $modules = Module::all();

            /** @var User $user */
            $user = User::where('email', $email)->firstOrFail();
            $contact = $this->infusionsoftService->getContact($email);

            $startModuleReminderHelper = new StartModuleReminderHelper($user, $contact['_Products'], $modules);
            $nextModule = $startModuleReminderHelper->getNextModule();

            /** @var Collection|StartModuleReminderCollection $tags */
            $tag = $tags->getTagByModuleName($nextModule->name);
            $status = $this->infusionsoftService->addTag($contact['Id'], $tag->original_id);
            $message = $status ? $tag->name : 'Reminder could not be set';
        } catch (ModelNotFoundException $e) {
            $message = $e->getMessage();
        }

        return Response::json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
