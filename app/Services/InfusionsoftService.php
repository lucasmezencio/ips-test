<?php

namespace App\Services;

use Illuminate\Filesystem\FilesystemManager;
use Infusionsoft;
use Log;
use Storage;
use Request;

/**
 * Class InfusionsoftService
 *
 * @package App\Services
 */
class InfusionsoftService
{
    /** @var FilesystemManager */
    private $storage;

    /**
     * InfusionsoftService constructor.
     *
     * @param FilesystemManager|Storage $storage
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct(FilesystemManager $storage)
    {
        $this->storage = $storage;

        if ($this->storage->exists('inf_token')) {
            Infusionsoft::setToken(unserialize($this->storage->get('inf_token')));
        } else {
            Log::error('Infusionsoft token not set.');
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function authorize(Request $request): string
    {
        if ($request::has('code')) {
            Infusionsoft::requestAccessToken($request::get('code'));

            $token = Infusionsoft::getToken();

            $this->storage->put('inf_token', serialize($token));

            Log::notice('Infusionsoft token created');

            Infusionsoft::setToken($token);

            return 'Success';
        }

        return '<a href="' . Infusionsoft::getAuthorizationUrl() . '">Authorize Infusionsoft</a>';
    }

    /**
     * @return bool|Infusionsoft\InfusionsoftCollection
     */
    public function getAllTags()
    {
        try {
            return Infusionsoft::tags()->all();
        } catch (\Exception $e) {
            Log::error((string)$e);

            return false;
        }
    }

    /**
     * @param $email
     *
     * @return mixed
     */
    public function getContact($email)
    {
        $fields = [
            'Id',
            'Email',
            'Groups',
            '_Products',
        ];

        try {
            return Infusionsoft::contacts('xml')->findByEmail($email, $fields)[0];
        } catch (\Exception $e) {
            Log::error((string)$e);

            return false;
        }
    }

    /**
     * @param $contact_id
     * @param $tag_id
     *
     * @return bool
     */
    public function addTag($contact_id, $tag_id): bool
    {
        try {
            return Infusionsoft::contacts('xml')->addToGroup($contact_id, $tag_id);
        } catch (\Exception $e) {
            Log::error((string)$e);

            return false;
        }
    }

    /**
     * @param $data
     *
     * @return bool|int
     */
    public function createContact($data)
    {
        try {
            return Infusionsoft::contacts('xml')->add($data);
        } catch (\Exception $e) {
            Log::error((string)$e);

            return false;
        }
    }
}
