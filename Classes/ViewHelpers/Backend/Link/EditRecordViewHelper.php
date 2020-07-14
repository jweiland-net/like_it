<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like_it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\ViewHelpers\Backend\Link;

/**
 * @deprecated use original ViewHelper \TYPO3\CMS\Backend\ViewHelpers\Link\EditRecordViewHelper instead!
 */
class EditRecordViewHelper extends \TYPO3\CMS\Backend\ViewHelpers\Link\EditRecordViewHelper
{
    public function render(): string
    {
        trigger_error(
            'Using \JWeiland\LikeIt\ViewHelpers\Backend\Link\EditRecordViewHelper is deprecated, please use \TYPO3\CMS\Backend\ViewHelpers\Link\EditRecordViewHelper instead.',
            E_USER_DEPRECATED
        );
        return parent::render();
    }
}
