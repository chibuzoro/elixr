<?php


namespace App\Resources;


interface ResourceContract
{

    /**
     * load the default attributes for this resource
     * @return array
     */
    public function getDefaultAttributes(): array;

}
