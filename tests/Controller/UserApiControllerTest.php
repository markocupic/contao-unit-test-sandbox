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

use Contao\Model\Collection;
use Contao\TestCase\ContaoTestCase;
use Contao\UserModel;
use Markocupic\ContaoUnitTestSandbox\Controller\UserApiController;

class UserApiControllerTest extends ContaoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        //$container = $this->getContainerWithContaoConfiguration();
    }

    /**
     * @dataProvider listsUsersProvider
     */
    public function testListUsersAction(array $users): void
    {
        $userModels = [];

        foreach ($users as $user) {
            $userModelMock = $this->mockClassWithProperties(UserModel::class, $user);
            $userModelMock
                ->method('row')
                ->willReturn($user)
            ;

            $userModels[] = $userModelMock;
        }

        $collection = new Collection([...$userModels], 'tl_user');

        $eventAdapter = $this->mockAdapter(['findAll']);
        $eventAdapter
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($collection)
        ;

        $framework = $this->mockContaoFramework([
            UserModel::class => $eventAdapter,
        ]);

        $controller = new UserApiController($framework);

        // Call the getEventsAction method
        $response = $controller->listUsersAction();

        // Assert that the response contains the expected data
        $this->assertSame(['data' => $users], json_decode($response->getContent(), true));
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
    }
}
