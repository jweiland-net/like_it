<?php
declare(strict_types=1);
namespace JWeiland\LikeIt\Service;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

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
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
