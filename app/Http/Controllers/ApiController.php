<?php

namespace App\Http\Controllers;

use App\Repository\RepositoryContract;
use App\Traits\QueryTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ApiController extends Controller
{

    use QueryTraits;

    public const QUERY_RELATION_KEY = 'embed';
    public const QUERY_SORT_KEY = 'sort';
    public const QUERY_GROUP_KEY = 'group';
    public const QUERY_FIELDS_KEY = 'fields';
    public const QUERY_SEARCH_KEY = 'q';

    protected $statusCode = 200;

    protected $resourceManager;

    protected $config;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = Config::get('resource.repositories');
    }


    /**
     * Locate Repository
     * @param  string  $resource
     * @param  string  $version
     * @return RepositoryContract
     */
    private function getRepository(string $resource, $version): RepositoryContract
    {
        $repository = $this->config[$version][$resource]['repo'];
        return $this->getInstance($repository);

    }

    private function getInstance(string $identifier)
    {
        return app()->make($identifier);
    }

    private function validateVersion($version, $resource): bool
    {

        $repos = $this->config;

        return isset($repos[$version]) ? array_key_exists($resource, $repos[$version]) : false;
    }


    public function doPostAction(Request $request, string $version, string $resource)
    {

        if ($this->validateVersion($version, $resource) === false) {
            abort(404, 'resource not found');
        }

        $repository = $this->getRepository($resource, $version);


        try {
            // lets validate input values. never trust the client
            $data = $this->validate($request, $repository->getRules());
            $data = $this->transform($request, $version, $resource, $repository->store($data));

            return $this->respond($data);


        } catch (\Exception $e) {
            // todo catch exceptions and log errors
            return [
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ];
        }


    }

    public function doGetAction(Request $request, string $version, string $resource, $id = null)
    {

        if ($this->validateVersion($version, $resource) === false) {
            abort(404, 'resource not found');
        }

        $params = $this->getParams($request);
        $repository = $this->getRepository($resource, $version);


        try {

            $response = $id ? $repository->fetch($id, $params) : $repository->fetchAll($params);

            return $this->respond($response);

        } catch (\Exception $e) {
            // todo catch exceptions and log errors
            return [
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
                'trace' => $e->getTrace(),
            ];
        }


    }

    public function doUpdateAction(Request $request, string $version, string $resource, $id = null)
    {

        if ($this->validateVersion($version, $resource) === false) {
            abort(404, 'resource not found');
        }

        $repository = $this->getRepository($resource, $version);

        // lets validate input values. never trust the client
        if ($this->validate($request, $repository->getRules()) === false) {
            abort(422, 'invalid request');
        }


        $data = $request->all();

        try {
            $response = $repository->update($data, $id);
            return $this->respond($response);

        } catch (\Exception $e) {
            // todo catch exceptions and log errors
            return [
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
                'trace' => $e->getTrace(),
            ];
        }

    }

    public function doDeleteAction(Request $request, string $version, string $resource, $id)
    {

        if ($this->validateVersion($version, $resource) === false) {
            abort(404, 'resource not found');
        }

        $repository = $this->getRepository($resource, $version);

        try {

            $response = $repository->delete($id);

            return $this->respond($response);

        } catch (\Exception $e) {
            // todo catch exceptions and log errors
            return [
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
                'trace' => $e->getTrace(),
            ];
        }

    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param  int  $statusCode
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $statusCode = null)
    {
        $statusCode = $statusCode ?? $this->getStatusCode();
        // @todo format structure of response

        return response($data, $statusCode);
    }

    private function getParams(Request $request): array
    {
        $sort = $this->checkSort($request, self::QUERY_SORT_KEY);
        $group = $this->checkGroup($request, self::QUERY_GROUP_KEY);
        $fields = $this->checkFields($request, self::QUERY_FIELDS_KEY);
        $search = $this->checkSearch($request, self::QUERY_SEARCH_KEY);
        $filter = $this->checkFilter($request, [
            self::QUERY_SORT_KEY,
            self::QUERY_SEARCH_KEY,
            self::QUERY_RELATION_KEY,
            self::QUERY_FIELDS_KEY,
            self::QUERY_GROUP_KEY
        ]);

        return compact('sort', 'group', 'fields', 'search', 'filter');
    }

    public function transform($request, $version, $resource, $data)
    {
        $embeds = $this->checkRelation($request, self::QUERY_RELATION_KEY);
        $transformer = $this->config[$version][$resource]['transformer'];
        return new $transformer($data);

    }


}
