<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Ajax;

use JWeiland\LikeIt\Domain\Repository\LikeRepository;
use JWeiland\LikeIt\Utility\CookieUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
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
        'message' => '',
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

    public function processRequest(ServerRequestInterface $request): ResponseInterface
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

        return new JsonResponse($this->responseArray);
    }

    protected function initializeLanguage(): void
    {
        if (!isset($GLOBALS['LANG']) || !\is_object($GLOBALS['LANG'])) {
            $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
            $GLOBALS['LANG']->init('default');
        }
    }

    protected function initializeCookie(): void
    {
        $this->cookieValue = CookieUtility::getCookieValue();
    }

    protected function initializeForeignData(ServerRequestInterface $request): void
    {
        if (
            !isset($request->getQueryParams()['table'])
            || !$table = (string)$request->getQueryParams()['table']
        ) {
            throw new \UnexpectedValueException('No or unknown liked table passed!', 1543419903286);
        }

        if (
            !isset($request->getQueryParams()['uid'])
            || !$uid = (int)$request->getQueryParams()['uid']
        ) {
            throw new \UnexpectedValueException('No or unknown liked uid passed!', 1543419956387);
        }

        $this->table = $table;
        $this->uid = $uid;
    }

    protected function translate(string $key): string
    {
        return $GLOBALS['LANG']->sL('LLL:EXT:like_it/Resources/Private/Language/locallang.xlf:' . $key);
    }

    /**
     * Add a new like
     */
    protected function addAction(): void
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
     */
    protected function checkAction(): void
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
     */
    protected function removeAction(): void
    {
        if (!$this->likeRepository->removeByRecord($this->table, $this->uid, $this->cookieValue)) {
            $this->responseArray['hasErrors'] = true;
            $this->responseArray['message'] = $this->translate('message.could_not_remove_like');
        }
    }

    /**
     * Toggle like for liked table and uid with cookieValue.
     * Will additionally execute the checkAction to provide the values liked and amountOfLikes.
     */
    protected function toggleAction(): void
    {
        if ($this->likeRepository->findByRecord($this->table, $this->uid, $this->cookieValue)) {
            $this->removeAction();
        } else {
            $this->addAction();
        }

        $this->checkAction();
    }
}
