<?php

namespace PHPAether\Tests;

use PHPAether\Tests\BaseTestCase;

class MockHTTPRequestTestCase extends BaseTestCase
{

    protected const TEST_ROUTES = [
        '/' => [
            'controller'    => 'Home',
            'action'        => 'index',
            'methods'       => ['GET', 'POST']
        ],
        '/login' => [
            'controller'    => 'Auth',
            'GET'   => [
                'action'    => 'loginView'
            ],
            'POST'  => [
                'action'    => 'login'
            ]
        ],
        '/register' => [
            'controller'    => 'Auth',
            'action'        => 'register',
            'methods'       => ['POST']
        ],
        '/user/profile/update' => [
            'controller' => 'User',
            'GET'   => [
                'action' => 'userProfileView'
            ],
            'PUT'  => [
                'action' => 'updateUserProfile'
            ]
        ],
        '/user/dashboard' => [
            'controller'    => 'User',
            'action'        => 'userDashboardView'
        ],
        'book/:id/edit' => [
            'controller'    => 'Book',
            'action'        => 'editBook',
            'methods'       => ['POST', 'PUT'],
            'parameters'    => [
                'id'    => [
                    'type'  => 'number',
                ]
            ]
        ]
    ];

    public static function setUpHTTPRequestTest(
        string $route, string $method
    ): void
    {
        $_SERVER['REQUEST_URI'] = $route;
        $_SERVER['REQUEST_METHOD'] = $method;
    }

    public static function httpRequestDataProvider(): \Generator
    {

        $dataProviders = [
            'GET'   => [self::class, 'httpGETRequestDataProvider'],
            'POST'  => [self::class, 'httpPOSTRequestDataProvider'],
            'PUT'   => [self::class, 'httpPUTRequestDataProvider'],
        ];

        foreach ($dataProviders as $requestMethod => $dataProvider) {

            $testCases = $dataProvider();
            foreach ($testCases as $testCase) {
                $route = $testCase[0];
                $testCase = self::buildHTTPRequestDataProviderTestCase(
                    $requestMethod, ...$testCase
                );

                yield "HTTP Request Test Case [$requestMethod] $route" => $testCase;
            }
        }
    }

    public static function buildHTTPRequestDataProviderTestCase(
        string $requestMethod,
        string $route,
        string $expectedRoute,
        array $expectedAction,
        string $expectedResponse
    ): array
    {
        return [
            'route'     => $route,
            'method'    => strtoupper($requestMethod),
            // expected values
            'expected'  => [
                'route'     => $expectedRoute,
                'action'    => $expectedAction,
                'response'  => $expectedResponse
            ]
        ];
    }

    public static function httpGETRequestDataProvider(): array
    {
        return  [
            // [$route, $_route, $_action, $_response] (_ means "expected")
            ['/', '/', ['Home', 'index'], ''],
            ['/login?r_url=/user/dashboard', '/login', ['Auth', 'loginView'], ''],
            ['/user/dashboard', '/user/dashboard', ['User', 'userDashboardView'], ''],
            ['/leaderboard?league=ruby', '/leaderboard', ['Error', 'notFound'], ''],
            ['/book/3/edit', '/book/3/edit', ['Book', 'editBook'], '']
        ];
    }

    public static function httpPOSTRequestDataProvider(): array
    {
        return [
            // [$route, $_route, $_action, $_response] (_ means "expected")
            ['/register', '/register', ['Auth', 'register'], ''],
            ['/login', '/login', ['Auth', 'login'], ''],
            ['/cart', '/cart', ['Error', 'notFound'], '']
        ];
    }

    public static function httpPUTRequestDataProvider(): array
    {
        return [
            // [$route, $_route, $_action, $_response] (_ means "expected")
            ['/user/profile/update', '/user/profile/update', ['User', 'updateUserProfile'], '']
        ];
    }

}
