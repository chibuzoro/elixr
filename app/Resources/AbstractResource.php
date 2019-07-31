<?php


namespace App\Resources;


use App\Traits\QueryTraits;
use Illuminate\Http\Resources\Json\Resource;

abstract class AbstractResource extends Resource implements ResourceContract
{
    use QueryTraits;

    public const RESOURCE_KEY = '';


    /**
     * map relational resource to expected resource names in route
     * @var array $includes
     */
    protected $includes;


    /**
     * list of requested attributes
     * @var array $showAttributes
     */

    private $showAttributes = [];


    /**
     * process all embed request
     * @param  array  $embeds
     * @return array
     */
    public function processIncludes(array $embeds)
    {
        $additional = [];

        foreach ($embeds as $relation => $attributes) {
            if ($relation === self::RESOURCE_KEY) {
                if (count($attributes) > 0) {
                    $this->showAttributes = $attributes;
                }

                continue;
            }

            $allowedRelation = $this->includes;

            if (isset($allowedRelation[$relation]) === false) {
                continue;
            }

            $additional[$relation] = $allowedRelation[$relation]::collection($this->whenLoaded($relation));

        }
        return $additional;
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @inheritDoc
     */
    public function toArray($request)
    {

        $embed = $this->checkRelation($request);
        $additional = $this->processIncludes($embed);


        return $this->transformer($additional);
    }


    public function filterAttributes(array $attributes): array
    {
        return array_filter($attributes, function ($attribute) {
            return empty($this->showAttributes) ?: in_array($attribute, $this->showAttributes, true);
        }, ARRAY_FILTER_USE_KEY);

    }


    /**
     * merge all attributes and prepare for transformation
     * @param  array  $includes
     * @return array
     */
    public function transformer($includes = [])
    {
        $data = $this->getDefaultAttributes();
        return array_merge($this->filterAttributes($data), $includes);

    }

}
