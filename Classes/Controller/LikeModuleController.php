<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like_it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Controller;

use JWeiland\LikeIt\Repository\LikeRepository;
use JWeiland\LikeIt\Service\LikedTableService;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class LikeModuleController
 */
class LikeModuleController extends ActionController
{
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

    /**
     * inject likeRepository
     *
     * @param LikeRepository $likeRepository
     */
    public function injectLikeRepository(LikeRepository $likeRepository): void
    {
        $this->likeRepository = $likeRepository;
    }

    /**
     * inject likedTableService
     *
     * @param LikedTableService $likedTableService
     */
    public function injectLikedTableService(LikedTableService $likedTableService): void
    {
        $this->likedTableService = $likedTableService;
    }

    /**
     * @param string $table
     */
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
