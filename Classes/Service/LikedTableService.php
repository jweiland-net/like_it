<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like_it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Service;

use JWeiland\LikeIt\Repository\LikeRepository;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class LikedTableService
 */
class LikedTableService
{
    /**
     * @var LikeRepository
     */
    protected $likeRepository;

    /**
     * LikedTableService constructor.
     */
    public function __construct()
    {
        $this->likeRepository = GeneralUtility::makeInstance(LikeRepository::class);
    }

    /**
     * Get array for table selection
     * To be used with f:form.select
     *
     * @return array
     */
    public function getArrayForTableSelection(): array
    {
        $options = [];
        foreach ($this->likeRepository->findAllInstalledLikedTables() as $table) {
            if (!isset($GLOBALS['TCA'][$table])) {
                continue;
            }
            $title = $GLOBALS['TCA'][$table]['ctrl']['title'];
            if (strpos($title, 'LLL:') === 0) {
                $title = $this->getLanguageService()->sL($title);
            }
            $options[$table] = $title;
        }
        return $options;
    }

    /**
     * @return LanguageService other namespace in v9
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
