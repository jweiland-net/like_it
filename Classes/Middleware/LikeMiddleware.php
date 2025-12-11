<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Middleware;

use JWeiland\LikeIt\Configuration\LikeConfiguration;
use JWeiland\LikeIt\Domain\Repository\LikeRepository;
use JWeiland\LikeIt\Utility\CookieUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;

readonly class LikeMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected LikeRepository $likeRepository,
        protected LanguageServiceFactory $languageServiceFactory,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getHeader('ext-like-it') !== ['handleLikes']) {
            return $handler->handle($request);
        }

        $postParameters = json_decode((string)$request->getBody(), true);

        $likeConfiguration = $this->getLikeRequest($postParameters);
        $languageService = $this->languageServiceFactory->createFromSiteLanguage(
            $request->getAttribute('language'),
        );

        switch (isset($postParameters['action']) ? (string)$postParameters['action'] : '') {
            case 'add':
                $this->addAction($likeConfiguration, $languageService);
                break;
            case 'check':
                $this->checkAction($likeConfiguration);
                break;
            case 'remove':
                $this->removeAction($likeConfiguration, $languageService);
                break;
            case 'toggle':
                $this->toggleAction($likeConfiguration, $languageService);
                break;
            default:
                throw new \UnexpectedValueException('No or unknown action passed!', 1543418482439);
        }

        return new JsonResponse($likeConfiguration->getResponseArray());
    }

    /**
     * Add a new like
     */
    protected function addAction(LikeConfiguration $likeConfiguration, LanguageService $languageService): void
    {
        if (
            !$this->likeRepository->findByRecord($likeConfiguration)
            && !$this->likeRepository->insertRecord($likeConfiguration)
        ) {
            $likeConfiguration->addErrorMessage(
                $this->translate('message.could_not_add_like', $languageService),
            );
        }
    }

    /**
     * Check if the user with cookieValue already liked the current thing
     */
    protected function checkAction(LikeConfiguration $likeConfiguration): void
    {
        if ($this->likeRepository->findByRecord($likeConfiguration)) {
            $likeConfiguration->setLiked(true);
        } else {
            $likeConfiguration->setLiked(false);
        }

        $likeConfiguration->setAmountOfLikes(
            $this->likeRepository->countByRecord(
                $likeConfiguration->getTable(),
                $likeConfiguration->getUid(),
            ),
        );
    }

    /**
     * Remove a like for foreign table and uid with cookieValue
     */
    protected function removeAction(LikeConfiguration $likeConfiguration, LanguageService $languageService): void
    {
        if (!$this->likeRepository->removeByRecord($likeConfiguration)) {
            $likeConfiguration->addErrorMessage(
                $this->translate('message.could_not_remove_like', $languageService),
            );
        }
    }

    /**
     * Toggle like for liked table and uid with cookieValue.
     * Will additionally execute the checkAction to provide the values liked and amountOfLikes.
     */
    protected function toggleAction(LikeConfiguration $likeConfiguration, LanguageService $languageService): void
    {
        if ($this->likeRepository->findByRecord($likeConfiguration)) {
            $this->removeAction($likeConfiguration, $languageService);
        } else {
            $this->addAction($likeConfiguration, $languageService);
        }

        $this->checkAction($likeConfiguration);
    }

    protected function translate(string $key, LanguageService $languageService): string
    {
        return $languageService->sL('LLL:EXT:like_it/Resources/Private/Language/locallang.xlf:' . $key);
    }

    protected function getLikeRequest(array $postParameters): LikeConfiguration
    {
        if (!isset($postParameters['table'])) {
            throw new \UnexpectedValueException('No or unknown liked table passed!', 1543419903286);
        }

        if (!isset($postParameters['uid'])) {
            throw new \UnexpectedValueException('No or unknown liked uid passed!', 1543419956387);
        }

        return new LikeConfiguration(
            (string)$postParameters['table'],
            (int)$postParameters['uid'],
            CookieUtility::getCookieValue(),
        );
    }
}
