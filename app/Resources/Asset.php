<?php


namespace App\Resources;


class Asset extends AbstractResource
{

    const RESOURCE_KEY = 'assets';


    public function getDefaultAttributes(): array
    {
        return [
            'title' => $this->title,
            'assetType' => $this->assetType,
            'assetPath' => $this->assetPath,
            'assetName' => $this->assetName,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }

}
