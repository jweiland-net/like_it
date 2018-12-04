<?php
declare(strict_types=1);
namespace JWeiland\LikeIt\ViewHelpers\Widget\Controller;

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
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

/**
 * Class RatingController
 */
class RatingController extends AbstractWidgetController
{
    /**
     * @var LikeRepository
     */
    protected $likeRepository;

    /**
     * @var PageRenderer
     */
    protected $pageRenderer;

    /**
     * inject likeRepository
     *
     * @param LikeRepository $likeRepository
     * @return void
     */
    public function injectRatingRepository(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    /**
     * inject pageRenderer
     *
     * @param PageRenderer $pageRenderer
     * @return void
     */
    public function injectPageRenderer(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->pageRenderer->addInlineSetting(
            'tx_likeit',
            'message_liked',
            LocalizationUtility::translate('message.liked', 'like_it')
        );

        $this->pageRenderer->addInlineSetting(
            'tx_likeit',
            'message_not_liked',
            LocalizationUtility::translate('message.not_liked', 'like_it')
        );

        $this
            ->view
            ->assign('amountOfLikes', $this->likeRepository->countByRecord(
                $this->widgetConfiguration['table'],
                $this->widgetConfiguration['uid']
            ))
            ->assign('table', $this->widgetConfiguration['table'])
            ->assign('uid', $this->widgetConfiguration['uid']);
    }
}
