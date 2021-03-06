<?php

namespace Ordermind\LogicalAuthorizationBundle\Tests\Functional\Services;

class LogicalAuthorizationRouteTest extends LogicalAuthorizationBase
{
    public function testRouteRoleAllow()
    {
        $this->sendRequestAs('GET', '/test/route-role', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteRoleMultipleAllow()
    {
        $this->sendRequestAs('GET', '/test/route-role-multiple', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteRoleDisallow()
    {
        $this->sendRequestAs('GET', '/test/route-role', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRouteBypassActionAllow()
    {
        $this->sendRequestAs('GET', '/test/route-role', [], static::$superadmin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteBypassActionDisallow()
    {
        $this->sendRequestAs('GET', '/test/route-no-bypass', [], static::$superadmin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRouteHostAllow()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'test.com']);
        $headers = [
      'PHP_AUTH_USER' => static::$authenticated_user->getUsername(),
      'PHP_AUTH_PW'   => $this->user_credentials[static::$authenticated_user->getUsername()],
    ];
        $client->request('GET', '/test/route-host', [], [], $headers);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteHostDisallow()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'test.se']);
        $headers = [
      'PHP_AUTH_USER' => static::$authenticated_user->getUsername(),
      'PHP_AUTH_PW'   => $this->user_credentials[static::$authenticated_user->getUsername()],
    ];
        $client->request('GET', '/test/route-host', [], [], $headers);
        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRouteMethodAllow()
    {
        $this->sendRequestAs('GET', '/test/route-method', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteMethodLowercaseAllow()
    {
        $this->sendRequestAs('GET', '/test/route-method-lowercase', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteMethodDisallow()
    {
        $this->sendRequestAs('PUSH', '/test/route-method', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRouteIpAllow()
    {
        $client = static::createClient([], ['REMOTE_ADDR' => '127.0.0.1']);
        $headers = [
      'PHP_AUTH_USER' => static::$authenticated_user->getUsername(),
      'PHP_AUTH_PW'   => $this->user_credentials[static::$authenticated_user->getUsername()],
    ];
        $client->request('GET', '/test/route-ip', [], [], $headers);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteIpDisallow()
    {
        $client = static::createClient([], ['REMOTE_ADDR' => '127.0.0.55']);
        $headers = [
      'PHP_AUTH_USER' => static::$authenticated_user->getUsername(),
      'PHP_AUTH_PW'   => $this->user_credentials[static::$authenticated_user->getUsername()],
    ];
        $client->request('GET', '/test/route-ip', [], [], $headers);
        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRouteHasAccountAllow()
    {
        $this->sendRequestAs('GET', '/test/route-has-account', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteHasAccountDisallow()
    {
        $this->sendRequestAs('GET', '/test/route-has-account', []);
        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testMultipleRoute1Allow()
    {
        $this->sendRequestAs('GET', '/test/multiple-route-1', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMultipleRoute2Allow()
    {
        $this->sendRequestAs('GET', '/test/multiple-route-2', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMultipleRoute1Disallow()
    {
        $this->sendRequestAs('GET', '/test/multiple-route-1', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testMultipleRoute2Disallow()
    {
        $this->sendRequestAs('GET', '/test/multiple-route-2', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testYmlRouteAllow()
    {
        $this->sendRequestAs('GET', '/test/route-yml', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testYmlRouteDisallow()
    {
        $this->sendRequestAs('GET', '/test/route-yml', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testYmlRouteBoolAllow()
    {
        $this->sendRequestAs('GET', '/test/route-yml-allowed', []);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testYmlRouteBoolDisallow()
    {
        $this->sendRequestAs('GET', '/test/route-yml-denied', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testXmlRouteAllow()
    {
        $this->sendRequestAs('GET', '/test/route-xml', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testXmlRouteDisallow()
    {
        $this->sendRequestAs('GET', '/test/route-xml', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testXmlRouteBoolAllow()
    {
        $this->sendRequestAs('GET', '/test/route-xml-allowed', []);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testXmlRouteBoolDisallow()
    {
        $this->sendRequestAs('GET', '/test/route-xml-denied', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRouteBoolAllow()
    {
        $this->sendRequestAs('GET', '/test/pattern-allowed', []);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteBoolDeny()
    {
        $this->sendRequestAs('GET', '/test/route-denied', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRouteComplexAllow()
    {
        $this->sendRequestAs('GET', '/test/route-complex', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRouteComplexDeny()
    {
        $this->sendRequestAs('GET', '/test/route-complex', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRoutePatternDenyAll()
    {
        $this->sendRequestAs('GET', '/test/route-forbidden', [], static::$superadmin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRoutePatternOverriddenAllow()
    {
        $this->sendRequestAs('GET', '/test/route-allowed', []);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRoutePatternOverriddenDeny()
    {
        $this->sendRequestAs('GET', '/test/pattern-forbidden', [], static::$superadmin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testAvailableRoutesAnonymous()
    {
        $this->sendRequestAs('GET', '/test/count-available-routes', []);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertGreaterThan(3, $routes_count);
    }

    public function testAvailableRoutesAuthenticated()
    {
        $this->sendRequestAs('GET', '/test/count-available-routes', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertGreaterThan(4, $routes_count);
    }

    public function testAvailableRoutesAdmin()
    {
        $this->sendRequestAs('GET', '/test/count-available-routes', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertGreaterThan(5, $routes_count);
    }

    public function testAvailableRoutesSuperadmin()
    {
        $this->sendRequestAs('GET', '/test/count-available-routes', [], static::$superadmin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertGreaterThan(5, $routes_count);
    }

    public function testAvailableRoutePatternsAnonymous()
    {
        $this->sendRequestAs('GET', '/test/count-available-route-patterns', []);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertEquals(1, $routes_count);
    }

    public function testAvailableRoutePatternsAuthenticated()
    {
        $this->sendRequestAs('GET', '/test/count-available-route-patterns', [], static::$authenticated_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertEquals(1, $routes_count);
    }

    public function testAvailableRoutePatternsAdmin()
    {
        $this->sendRequestAs('GET', '/test/count-available-route-patterns', [], static::$admin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertEquals(1, $routes_count);
    }

    public function testAvailableRoutePatternsSuperadmin()
    {
        $this->sendRequestAs('GET', '/test/count-available-route-patterns', [], static::$superadmin_user);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $routes_count = $response->getContent();
        $this->assertEquals(1, $routes_count);
    }
}
