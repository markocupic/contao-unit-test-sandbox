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

namespace Markocupic\ContaoUnitTestSandbox\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserApi2Controller extends AbstractController
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    #[Route('/user_api2', name: self::class, defaults: ['_scope' => 'frontend', '_token_check' => true])]
    public function listUsersAction(): JsonResponse
    {
        $arrUsers = $this->getUsers();
        $arrUsers = $this->mbConvertEncoding($arrUsers);

        $arrJson = ['data' => $arrUsers];

        return new JsonResponse($arrJson);
    }

    protected function getUsers(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('name')
            ->from('tl_user', 'u')
            ->where($qb->expr()->eq('admin', '1'))
        ;

        return $qb->fetchAllAssociative();
    }

    protected function mbConvertEncoding(array $arrData): array
    {
        $data = [];

        foreach ($arrData as $row) {
            $data[] = array_map(
                static fn ($varValue) => \is_string($varValue) ? mb_convert_encoding($varValue, 'UTF-8', 'UTF-8') : $varValue,
                $row,
            );
        }

        return $data;
    }
}
