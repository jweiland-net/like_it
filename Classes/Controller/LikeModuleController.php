<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Controller;

use JWeiland\LikeIt\Domain\Repository\LikeRepository;
use JWeiland\LikeIt\Service\LikedTableService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class LikeModuleController extends ActionController
{
    public function __construct(
        protected readonly LikeRepository $likeRepository,
        protected readonly LikedTableService $likedTableService,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    ) {}

    public function listAction(string $table = ''): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        $likedTables = $this->likedTableService->getArrayForTableSelection();
        $moduleTemplate->assign('likedTables', $likedTables);

        if (!$table) {
            $table = (string)key($likedTables);
        }

        $moduleTemplate->assign('table', $table);

        if ($table) {
            $moduleTemplate->assign('likedTableItems', $this->likeRepository->findLikedTableItems($table));
        }

        return $moduleTemplate->renderResponse('LikeModule/List');
    }
}
