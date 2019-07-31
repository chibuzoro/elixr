<?php

namespace Test;

use App\Http\Controllers\ApiController;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiTest extends \TestCase
{
    /**
     * A basic route test example.
     *
     * @return void
     */
    public function testRoutes()
    {
        // test list Qr collection
        $this->get('/api/v1/qrs');
        $this->assertResponseOk();

        // test detail Qr item
        $this->get('/api/v1/qrs/344');
        $this->assertResponseOk();

        // test create Qr collection
        $this->post('/api/v1/qrs');
        $this->assertResponseOk();

        // test update Qr collection
        $this->put('/api/v1/qrs');
        $this->assertResponseOk();

        // test update Qr item
        $this->put('/api/v1/qrs/344');
        $this->assertResponseOk();

        // test update Qr item
        $this->delete('/api/v1/qrs/344');
        $this->assertResponseOk();
    }

    public function testLocateResource()
    {
        $apiController = new ApiController();


    }

    public function testQueryParameter()
    {

    }
}
