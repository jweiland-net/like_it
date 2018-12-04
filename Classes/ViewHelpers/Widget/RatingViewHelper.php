<?php
declare(strict_types=1);
namespace JWeiland\LikeIt\ViewHelpers\Widget;

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
     * @return void
     */
    public function injectController(RatingController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return void
     */
    public function initializeArguments()
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
