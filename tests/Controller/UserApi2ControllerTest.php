<?php

declare(strict_types=1);

/*
 * This file is part of Unit Test Sandbox.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-unit-test-sandbox
 */

namespace Markocupic\ContaoUnitTestSandbox\Tests\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Markocupic\ContaoUnitTestSandbox\Controller\UserApi2Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserApi2ControllerTest extends TestCase
{
    /**
     * @dataProvider listsUsersProvider
     */
    public function testListUsersAction($users): void
    {
        $controller = new UserApi2Controller($this->mockConnectionForQuery('tl_user', $users));

        // Make protected method accessible
        $method = new \ReflectionMethod(UserApi2Controller::class, 'mbConvertEncoding');
        $method->setAccessible(true);
        $users = $method->invoke($controller, $users);

        // Set up the expected response
        $expectedResponseData = ['data' => $users];

        $expectedResponse = new JsonResponse($expectedResponseData);

        // Call the method to be tested
        $actualResponse = $controller->listUsersAction();

        // Assert that the actual response matches the expected response
        $this->assertSame($expectedResponse->getContent(), $actualResponse->getContent());
    }

    public function listsUsersProvider(): \Generator
    {
        yield 'first user collection' => [
            [
                ['id' => 1, 'username' => 'k.jones', 'name' => 'Kevin Jones', 'email' => 'k.jones@example.com', 'admin' => '1'],
                ['id' => 2, 'username' => 'j.wilson', 'name' => 'James Wilson', 'email' => 'j.wilson@example.com', 'admin' => ''],
                ['id' => 3, 'username' => 'h.lewis', 'name' => 'Helen Lewis', 'email' => 'h.lewis@example.com', 'admin' => ''],
            ],
        ];

        yield 'second user collection' => [
            [
                ['id' => 1, 'username' => 'k.jones', 'name' => 'Kevin Jones', 'email' => 'k.jones@example.com', 'admin' => '1'],
                ['id' => 2, 'username' => 'j.wilson', 'name' => 'James Wilson', 'email' => 'j.wilson@example.com', 'admin' => ''],
                ['id' => 3, 'username' => 'h.lewis', 'name' => 'Helen Lewis', 'email' => 'h.lewis@example.com', 'admin' => ''],
            ],
        ];
    }

    private function mockConnectionForQuery(string $table, array $data): Connection
    {
        $exprWhere = $this->createMock(ExpressionBuilder::class);
        $exprWhere
            ->expects($this->once())
            ->method('eq')
            ->with('admin', '1')
            ->willReturnSelf()
        ;

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder
            ->expects($this->once())
            ->method('expr')
            ->willReturn($exprWhere)
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('select')
            ->with('name')
            ->willReturnSelf()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('from')
            ->with($table)
            ->willReturnSelf()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('where')
            ->with($exprWhere)
            ->willReturnSelf()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('fetchAllAssociative')
            ->willReturn($data)
        ;

        $connection = $this->createMock(Connection::class);
        $connection
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder)
        ;

        return $connection;
    }
}
