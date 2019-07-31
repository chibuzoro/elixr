<?php


namespace App\Repository;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements RepositoryContract
{

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var Model
     */
    protected $model;

    public function fetch($id, $params = [])
    {
        // TODO: Implement fetch() method.
    }

    public function fetchAll($params = [])
    {
        // TODO: Implement fetchAll() method.
    }

    public function store($data)
    {
        // TODO: Implement store() method.
    }

    public function update($data, $id = null)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function buildLink(string $path, $params = []): string
    {
        $link = url('api/v1'). DIRECTORY_SEPARATOR . $path;

        if (empty($params) === false){
            $link .= '?';
            $link .= http_build_query($params, null, '&');
        }

        return $link;
    }


}
