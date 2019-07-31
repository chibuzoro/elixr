<?php


namespace tests;




use App\Traits\QueryTraits;

class QueryHelperTest extends \TestCase
{
   use QueryTraits;



    function testSearchParams(){

        $request = $this->app->request;
        $request->merge([
            'q' => 'rat,mouse,life',
            'fields' => 'animals,specie'
        ]);
        $this->assertEquals([
            'rat,mouse,life'
        ], $this->checkSearch($request));
    }


    function testSearchParamsEmpty(){

        $request = $this->app->request;
        $request->merge([
            'fields' => 'animals,specie',
            'q' => ''
        ]);
        $this->assertEmpty( $this->checkSearch($request), 'checking search params');
    }

    function testFieldParams(){

        $request = $this->app->request;
        $request->merge([
            'q' => 'rat,mouse,life',
            'fields' => 'animals,specie'
        ]);
        $this->assertEquals([
            'animals','specie'
        ], $this->checkFields($request));
    }


    function testFieldParamsEmpty(){

        $request = $this->app->request;
        $request->merge([
            'q' => 'rat,mouse,life',
            'fields' => ''
        ]);
        $this->assertEmpty( $this->checkFields($request), 'checking fields params');
    }



    function testFilterParams(){

        $request = $this->app->request;
        $request->merge([
            'fields' => 'animals,specie',
            'q' => 'rat,mouse,life',
            'state' => 'ok'
        ]);
        $this->assertEquals([
            'state' => 'ok'
        ], $this->checkFilter($request, [
            'fields', 'q'
        ]));
    }

    function testFilterParamsEmpty(){

        $request = $this->app->request;
        $request->merge([
            'fields' => 'animals,specie',
            'q' => 'rat,mouse,life',
        ]);
        $this->assertEmpty( $this->checkFilter($request, [
            'fields', 'q'
        ]), 'checking filter params');
    }

    function testSortParams(){

        $request = $this->app->request;
        $request->merge([
            'sort' => '-animals,specie',
            'q' => 'rat,mouse,life',
            'state' => 'ok'
        ]);
        $this->assertEquals([
            'animals' => 'desc',
            'specie' => 'asc',
        ], $this->checkSort($request));
    }

    function testSortParamsEmpty(){

        $request = $this->app->request;
        $request->merge([
            'fields' => 'animals,specie',
            'q' => 'rat,mouse,life',
        ]);
        $this->assertEmpty( $this->checkSort($request), 'checking sort params');
    }

    function testRelationParams(){

        $request = $this->app->request;
        $request->merge([
            'sort' => '-animals,specie',
            'q' => 'rat,mouse,life',
            'embed' => 'house.doors.windows, varander.tiles'
        ]);
        $this->assertEquals([
            'house' => ['doors', 'windows'],
            'varander' => ['tiles'],
        ], $this->checkRelation($request));
    }

    function testRelationParamsEmpty(){

        $request = $this->app->request;
        $request->merge([
            'sort' => '-animals,specie',
            'q' => 'rat,mouse,life',
        ]);
        $this->assertEmpty( $this->checkRelation($request), 'checking sort params');
    }


}
