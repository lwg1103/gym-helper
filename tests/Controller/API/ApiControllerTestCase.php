<?php

namespace App\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class ApiControllerTestCase extends WebTestCase
{
    protected $client;
    
    public function __construct()
    {
//        $this->client = new Client(['base_uri' => 'http://localhost:8000']);
    }
}
