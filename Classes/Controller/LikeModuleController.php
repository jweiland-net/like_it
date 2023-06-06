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

/**
 * Class LikeModuleController
 */
class LikeModuleController extends ActionController
{
    /**
     * @var LikeRepository
     */
    protected $likeRepository;

    /**
     * @var LikedTableService
     */
    protected $likedTableService;

    public function injectLikeRepository(LikeRepository $likeRepository): void
    {
        $this->likeRepository = $likeRepository;
    }

    public function injectLikedTableService(LikedTableService $likedTableService): void
    {
        $this->likedTableService = $likedTableService;
    }
    protected ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function listAction(string $table = ''): ResponseInterface
    {
        $likedTables = $this->likedTableService->getArrayForTableSelection();
        $this->view->assign('likedTables', $likedTables);

        if (!$table) {
            $table = (string)key($likedTables);
        }

        $this->view->assign('table', $table);

        if ($table) {
            $this->view->assign('likedTableItems', $this->likeRepository->findLikedTableItems($table));
        }
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
