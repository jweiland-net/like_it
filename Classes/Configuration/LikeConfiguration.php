<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Configuration;

class LikeConfiguration
{
    protected bool $liked = false;

    protected int $amountOfLikes = 0;

    protected array $errorMessage = [];

    public function __construct(
        protected string $table,
        protected int $uid,
        protected string $cookieValue,
    ) {}

    public function getTable(): string
    {
        return $this->table;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function getCookieValue(): string
    {
        return $this->cookieValue;
    }

    public function isLiked(): bool
    {
        return $this->liked;
    }

    public function setLiked(bool $liked): void
    {
        $this->liked = $liked;
    }

    public function getAmountOfLikes(): int
    {
        return $this->amountOfLikes;
    }

    public function setAmountOfLikes(int $amountOfLikes): void
    {
        $this->amountOfLikes = $amountOfLikes;
    }

    public function addErrorMessage(string $errorMessage): void
    {
        $this->errorMessage[] = $errorMessage;
    }

    public function getResponseArray(): array
    {
        $responseArray = [];
        $responseArray['liked'] = $this->liked;
        $responseArray['amountOfLikes'] = $this->amountOfLikes;
        $responseArray['hasErrors'] = $this->errorMessage !== [];
        $responseArray['message'] = implode(', ', $this->errorMessage);

        return $responseArray;
    }
}
