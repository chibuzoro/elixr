<?php


namespace App\Resources;


class Qr extends AbstractResource
{
    const RESOURCE_KEY = 'qrs';

    protected $includes = [
        'assets' => Asset::class,
    ];


    public function getDefaultAttributes(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'imgName' => $this->imgName,
            'imgLink' => $this->imgLink,
        ];
    }


}
