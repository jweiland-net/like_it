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
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class LikeModuleController
 */
class LikeModuleController extends ActionController
{
    /**
     * The default view object to use if none of the resolved views can render
     * a response for the current request.
     *
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * @var BackendTemplateView
     */
    protected $view;

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

    public function listAction(string $table = ''): void
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
    }
}
