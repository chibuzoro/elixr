<?php


namespace App\Repository;


interface RepositoryContract
{


    /**
     * get single record from entity
     * @param $id
     * @return mixed
     */
    public function fetch($id, $params = []);

    /**
     * get a collection of entities
     * @return mixed
     */
    public function fetchAll($params = []);

    /**
     * Add record(s)
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * update a record
     * @param $data
     * @param  null  $id
     * @return mixed
     */
    public function update($data, $id = null);

    /**
     * delete a record
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * validation rules
     * @return array
     */
    public function getRules();

}
