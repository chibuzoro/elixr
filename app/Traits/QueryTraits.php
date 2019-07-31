<?php


namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait QueryTraits
{


    /**
     * extracts the fields that needs to be sorted including their sorting priority.
     * A negative (-) prefix suggest a descending order.
     * Order matters.
     * @param Request $request
     * @param string $key
     * @return array
     */
    protected function checkSort(Request $request, string $key = 'sort'): array
    {

        $params = [];

        if ($request->has($key)) {
            $query = $request->get($key);
            $extractedParams = $this->extractParam($query);

            $params = array_reduce($extractedParams, static function($carry,$val){

                $carry[$val] = 'asc';  // default ordering

                // check for presence of order flag expected at first string pos
                if (strpos($val, '-') === 0){
                    array_pop($carry);
                    $carry[substr($val, 1)] =  'desc';

                }

                return $carry;

            },[]);

        }

        return $params;

    }

    /**
     * extracts the columns that a groupby operation should affect. Order matters.
     * @param Request $request
     * @param string $key
     * @return array
     */
    protected function checkGroup(Request $request, string $key = 'group'): array
    {

        $params = [];

        if ($request->has($key)) {
            $query = $request->get($key);
            $params = $this->extractParam($query);

        }

        return $params;

    }

    /**
     * Extracts the columns that should be returned by a query. if not set all columns will be returned
     * @param Request $request
     * @param string $key
     * @return array
     */
    protected function checkFields(Request $request, string $key = 'fields'): array
    {
        $params = [];

        if ($request->has($key)) {
            $query = $request->get($key);
            $params = $this->extractParam($query);

        }

        return $params;

    }

    /**
     * Extracts the value that a result may be composed of.
     * Useful for scenarios involving more complex search or full-test search using ElasticSearch or Lucene
     * @param Request $request
     * @param string $key
     * @return array
     */
    protected function checkSearch(Request $request, string $key = 'q'): array
    {
        $params = [];

        if ($request->has($key)){
            $value = trim($request->get($key));
            $params = empty($value) ? [] : [$value];
        }

        return $params;

    }

    /**
     * Extracts the values that a result may be filtered by.
     * @param Request $request
     * @param array $keysToSkip
     * @return array
     */
    protected function checkFilter(Request $request, array $keysToSkip = []): array
    {

        $query = $request->query();

        $params = array_filter($query, static function ($key) use ($keysToSkip) {
            return in_array(strtolower($key), $keysToSkip, true) === false;
        }, ARRAY_FILTER_USE_KEY);


        return $params;

    }

    /**
     * Extracts the values that determined the relation to be returned alongside a result.
     * @todo implement nested relationships
     * @param Request $request
     * @param string $key
     * @return array
     */
    protected function checkRelation(Request $request, string $key = 'embed'): array
    {
        $params = [];

        if ($request->has($key)) {
            $query = $request->get($key);
            $extractedParams = $this->extractParam($query);

            // we check for specific fields to be returned by the relation
            foreach ($extractedParams as $fields){
                $fields = $this->extractParam($fields, '.');
                $relation = array_shift($fields);
                $params[$relation] = $fields;
            }

        }

        return $params;

    }

    private function extractParam(string $data, string $delimiter = ','): array
    {

        return array_filter(explode($delimiter, trim($data)));
    }
}
