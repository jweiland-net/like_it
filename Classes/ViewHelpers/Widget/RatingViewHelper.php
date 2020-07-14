<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like_it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\ViewHelpers\Widget;

use JWeiland\LikeIt\ViewHelpers\Widget\Controller\RatingController;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 * Class RatingViewHelper
 *
 * To display the like system you need to add the following view helper call to your
 * fluid template.
 *
 * Add the namespace to the beginning of the Fluid file
 * e.g. {namespace jw=JWeiland\LikeIt\ViewHelpers}
 *
 * Then put this at the position you wanna display the like button
 * e.g. <jw:widget.like table="tx_news_domain_model_news" uid="5" />
 *
 * Notice: This system runs with ajax so you need to add the static template to the template.
 */
class RatingViewHelper extends AbstractWidgetViewHelper
{
    /**
     * inject controller
     *
     * @param RatingController $controller
     */
    public function injectController(RatingController $controller): void
    {
        $this->controller = $controller;
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('table', 'string', 'The table of the record that can be liked', true);
        $this->registerArgument('uid', 'int', 'The UID of the record that can be liked', true);
    }

    /**
     * @return ResponseInterface
     */
    public function render(): ResponseInterface
    {
        return $this->initiateSubRequest();
    }
}
