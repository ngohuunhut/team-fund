<?php

namespace Nhn\Demo\Test;

use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\Folder;
use Mockery as m;
use Webklex\PHPIMAP\Message;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NhnTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     */
    public function test_has_macro_nhn(): void
    {
        $this->assertTrue(Collection::hasMacro('nhn'));
    }
}
