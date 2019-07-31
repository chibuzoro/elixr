<?php


namespace App\Repository;


class QrAssetRepository extends AbstractRepository
{

    public function __construct(Qr $qr)
    {
        $this->model = $qr;
        $this->rules = [
            'title' => 'string|required',
            'description' => 'string',
            'status' => 'int|required'
        ];
    }

}
