<?php
declare(strict_types=1);
namespace JWeiland\LikeIt\Controller;

/*
 * This file is part of the like_it project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

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
     * @return void
     */
    public function injectLikeRepository(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    /**
     * inject likedTableService
     *
     * @param LikedTableService $likedTableService
     * @return void
     */
    public function injectLikedTableService(LikedTableService $likedTableService)
    {
        $this->likedTableService = $likedTableService;
    }

    /**
     * @param string $table
     * @return void
     */
    public function listAction(string $table = '')
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
