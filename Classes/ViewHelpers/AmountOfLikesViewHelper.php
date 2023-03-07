<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\ViewHelpers;

use JWeiland\LikeIt\Domain\Repository\LikeRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * SF: I don't like repositories in ViewHelpers, but as like_it delivers a service for other extensions
 * it is easier for the integrators to just override the partials path and make use of this ViewHelper
 * instead of extending the controller of their extension to just add the amount of likes to view.
 */
class AmountOfLikesViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'table',
            'string',
            'Set the table name to get the rating for',
            true
        );

        $this->registerArgument(
            'uid',
            'int',
            'Set the UID of the record to get the rating for',
            true
        );
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): int {
        $table = $arguments['table'] ?? '';
        $uid = $arguments['uid'] ?? 0;

        // Early return, if arguments contain empty values
        if ($table !== '' && $uid <= 0) {
            return 0;
        }

        return self::getLikeRepository()->countByRecord($table, $uid);
    }

    private static function getLikeRepository(): LikeRepository
    {
        return GeneralUtility::makeInstance(LikeRepository::class);
    }
}
