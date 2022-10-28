<?php

namespace Tests\Feature\Proxy;

use App\Services\Proxy\ProxyLocator;
use App\Services\Proxy\ProxyRequest;
use Mockery\MockInterface;
use Tests\TestCase;

class proxyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_make_request_to_given_uri(): void
    {
        \Mockery::mock(ProxyLocator::class, fn(MockInterface $mock) => (
            $mock->shouldReceive('getProxyScrape')
        ));

        $expected = app(ProxyRequest::class)->getHeaderFrom('https://www.google.co.uk/');

        $this->assertNotNull($expected);
        $this->assertIsString($expected);
    }
}
