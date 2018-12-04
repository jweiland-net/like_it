<?php
declare(strict_types=1);
namespace JWeiland\LikeIt\Ajax;

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
use JWeiland\LikeIt\Utility\CookieUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class LikeController
 */
class LikeController
{
    /**
     * @var LikeRepository
     */
    protected $likeRepository;

    /**
     * @var array
     */
    protected $responseArray = [
        'hasErrors' => false,
        'message' => ''
    ];

    /**
     * @var string
     */
    protected $cookieValue = '';

    /**
     * @var int
     */
    protected $uid = 0;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function processRequest(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->initializeLanguage();
        $this->initializeCookie();
        $this->initializeForeignData($request);
        $this->likeRepository = GeneralUtility::makeInstance(LikeRepository::class);

        switch (isset($request->getQueryParams()['action']) ? (string)$request->getQueryParams()['action'] : '') {
            case 'add':
                $this->addAction();
                break;
            case 'check':
                $this->checkAction();
                break;
            case 'remove':
                $this->removeAction();
                break;
            case 'toggle':
                $this->toggleAction();
                break;
            default:
                throw new \UnexpectedValueException('No or unknown action passed!', 1543418482439);
        }

        $this->processResponse($response);
        return $response;
    }

    /**
     * @return void
     */
    protected function initializeLanguage()
    {
        if (!isset($GLOBALS['LANG']) || !\is_object($GLOBALS['LANG'])) {
            $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
            $GLOBALS['LANG']->init('default');
        }
    }

    /**
     * @return void
     */
    protected function initializeCookie()
    {
        $this->cookieValue = CookieUtility::getCookieValue();
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    protected function initializeForeignData(ServerRequestInterface $request)
    {
        if (
            !isset($request->getQueryParams()['liked_table'])
            || !$table = (string)$request->getQueryParams()['liked_table']
        ) {
            throw new \UnexpectedValueException('No or unknown liked table passed!', 1543419903286);
        }
        if (
            !isset($request->getQueryParams()['liked_uid'])
            || !$uid = (int)$request->getQueryParams()['liked_uid']
        ) {
            throw new \UnexpectedValueException('No or unknown liked uid passed!', 1543419956387);
        }
        $this->table = $table;
        $this->uid = $uid;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function translate(string $key): string
    {
        return $GLOBALS['LANG']->sL('LLL:EXT:like_it/Resources/Private/Language/locallang.xlf:' . $key);
    }

    /**
     * @param ResponseInterface $response
     * @return void
     */
    protected function processResponse(ResponseInterface &$response)
    {
        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(\json_encode($this->responseArray));
    }

    /**
     * Add a new like
     *
     * @return void
     */
    protected function addAction()
    {
        if (
            !$this->likeRepository->findByRecord($this->table, $this->uid, $this->cookieValue)
            && !$this->likeRepository->insertRecord($this->table, $this->uid, $this->cookieValue)
        ) {
            $this->responseArray['hasErrors'] = true;
            $this->responseArray['message'] = $this->translate('message.could_not_add_like');
        }
    }

    /**
     * Check if the user with cookieValue already liked the current thing
     *
     * @return void
     */
    protected function checkAction()
    {
        if ($this->likeRepository->findByRecord($this->table, $this->uid, $this->cookieValue)) {
            $this->responseArray['liked'] = true;
        } else {
            $this->responseArray['liked'] = false;
        }
        $this->responseArray['amountOfLikes'] = $this->likeRepository->countByRecord(
            $this->table,
            $this->uid
        );
    }

    /**
     * Remove a like for foreign table and uid with cookieValue
     *
     * @return void
     */
    protected function removeAction()
    {
        if (!$this->likeRepository->removeByRecord($this->table, $this->uid, $this->cookieValue)) {
            $this->responseArray['hasErrors'] = true;
            $this->responseArray['message'] = $this->translate('message.could_not_remove_like');
        }
    }

    /**
     * Toggle like for liked table and uid with cookieValue.
     * Will additionally execute the checkAction to provide the values liked and amountOfLikes.
     *
     * @return void
     */
    protected function toggleAction()
    {
        if ($this->likeRepository->findByRecord($this->table, $this->uid, $this->cookieValue)) {
            $this->removeAction();
        } else {
            $this->addAction();
        }
        $this->checkAction();
    }
}
