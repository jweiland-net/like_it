<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like_it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\ViewHelpers\Widget\Controller;

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
     */
    public function injectRatingRepository(LikeRepository $likeRepository): void
    {
        $this->likeRepository = $likeRepository;
    }

    /**
     * inject pageRenderer
     *
     * @param PageRenderer $pageRenderer
     */
    public function injectPageRenderer(PageRenderer $pageRenderer): void
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function indexAction(): void
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
