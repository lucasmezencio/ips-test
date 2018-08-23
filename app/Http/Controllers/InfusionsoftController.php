<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Request;
use Response;

use App\Services\InfusionsoftService;

class InfusionsoftController extends Controller
{
    /** @var InfusionsoftService */
    private $infusionsoftService;

    /**
     * InfusionsoftController constructor.
     *
     * @param InfusionsoftService $infusionsoftService
     */
    public function __construct(InfusionsoftService $infusionsoftService)
    {
        $this->infusionsoftService = $infusionsoftService;
    }

    public function authorizeInfusionsoft(Request $request): string
    {
        return $this->infusionsoftService->authorize($request);
    }

    public function testInfusionsoftIntegrationGetEmail($email): JsonResponse
    {
        return Response::json($this->infusionsoftService->getContact($email));
    }

    public function testInfusionsoftIntegrationAddTag($contact_id, $tag_id): JsonResponse
    {
        return Response::json($this->infusionsoftService->addTag($contact_id, $tag_id));
    }

    public function testInfusionsoftIntegrationGetAllTags(): JsonResponse
    {
        return Response::json($this->infusionsoftService->getAllTags());
    }

    public function testInfusionsoftIntegrationCreateContact(string $email): JsonResponse
    {
        return Response::json($this->infusionsoftService->createContact([
            'Email' => $email,
            '_Products' => 'ipa,iea',
        ]));
    }
}
