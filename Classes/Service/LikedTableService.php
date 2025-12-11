<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Service;

use JWeiland\LikeIt\Domain\Repository\LikeRepository;
use TYPO3\CMS\Core\Localization\LanguageService;

class LikedTableService
{
    public function __construct(
        protected readonly LikeRepository $likeRepository,
    ) {}

    /**
     * Get array for table selection
     * To be used with f:form.select
     */
    public function getArrayForTableSelection(): array
    {
        $options = [];
        foreach ($this->likeRepository->findAllInstalledLikedTables() as $table) {
            if (!isset($GLOBALS['TCA'][$table])) {
                continue;
            }

            $title = $GLOBALS['TCA'][$table]['ctrl']['title'];
            if (str_starts_with($title, 'LLL:')) {
                $title = $this->getLanguageService()->sL($title);
            }

            $options[$table] = $title;
        }

        return $options;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
