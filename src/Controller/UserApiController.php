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

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Model\Collection;
use Contao\UserModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserApiController extends AbstractController
{
    public function __construct(
        private readonly ContaoFramework $framework,
    ) {
    }

    #[Route('/user_api', name: self::class, defaults: ['_scope' => 'frontend', '_token_check' => true])]
    public function listUsersAction(): JsonResponse
    {
        $this->framework->initialize();

        $arrJson = ['data' => []];

        if (null !== ($users = $this->getUsers())) {
            while ($users->next()) {
                $arrJson['data'][] = array_map(
                    static fn ($varValue) => \is_string($varValue) ? mb_convert_encoding($varValue, 'UTF-8', 'UTF-8') : $varValue,
                    $users->row(),
                );
            }
        }

        return new JsonResponse($arrJson);
    }

    protected function getUsers(): Collection|null
    {
        $userModel = $this->framework->getAdapter(UserModel::class);

        return $userModel->findAll();
    }
}
