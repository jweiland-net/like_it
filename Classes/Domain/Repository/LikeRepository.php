<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/like-it.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\LikeIt\Domain\Repository;

use JWeiland\LikeIt\Configuration\LikeConfiguration;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/**
 * Repository to add, remove or check for existing like records
 */
class LikeRepository
{
    private const TABLE_NAME = 'tx_likeit_like';

    public function __construct(
        protected readonly ConnectionPool $connectionPool,
    ) {}

    public function findByRecord(LikeConfiguration $likeConfiguration): array
    {
        return $this
            ->getConnection()
            ->select(
                ['*'],
                self::TABLE_NAME,
                [
                    'liked_table' => $likeConfiguration->getTable(),
                    'liked_uid' => $likeConfiguration->getUid(),
                    'cookie_value' => $likeConfiguration->getCookieValue(),
                ],
            )
            ->fetchAssociative() ?: [];
    }

    /**
     * Find all installed liked tables
     * e.g. ['tx_news_domain_model_news', 'tt_content']
     */
    public function findAllInstalledLikedTables(): array
    {
        $rows = $this
            ->getConnection()
            ->select(
                ['liked_table'],
                self::TABLE_NAME,
                [],
                ['liked_table'],
            )
            ->fetchAllAssociative();

        $tables = [];
        foreach ($rows as $row) {
            // Only show currently registered tables
            if (!isset($GLOBALS['TCA'][$row['liked_table']])) {
                continue;
            }
            $tables[] = $row['liked_table'];
        }

        return $tables;
    }

    /**
     * Find liked table items for $table. This will add the label
     * of the records using TCA of $table.
     * E.g., findLikedTableItems('tx_news_domain_model_news')
     * will return sth. Like: '[1 => ['label' => 'Hello World', 'amount' => 512], 23 => 'label' => 'My new garden', 'amount' => 23]]
     */
    public function findLikedTableItems(string $table): array
    {
        if (!isset($GLOBALS['TCA'][$table])) {
            throw new \UnexpectedValueException(
                'Could not find "' . $table . '" in TCA!',
                1765460495,
            );
        }

        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('l.liked_uid')
            ->addSelectLiteral($queryBuilder->expr()->count('*', 'amount'))
            ->from(self::TABLE_NAME, 'l')
            ->where($queryBuilder->expr()->eq('l.liked_table', ':likedTable'))
            ->setParameter('likedTable', $table)
            ->groupBy('l.liked_uid')
            ->orderBy('amount', 'DESC');

        $this->addHiddenAndDeleteFieldCheck($queryBuilder, 'l', $table);

        $rows = $queryBuilder
            ->executeQuery()
            ->fetchAllAssociative();

        $items = [];
        foreach ($rows as $row) {
            $items[$row['liked_uid']] = [
                'label' => $this->findLabelForTableItem($table, (int)$row['liked_uid']),
                'amount' => $row['amount'],
            ];
        }

        return $items;
    }

    /**
     * Add hidden and delete field checks extracted from TCA
     * to the current QueryBuilder instance.
     * You need to check if $GLOBALS['TCA'][$table] exists!
     *
     * @param string $joinAlias default: e (= external)
     */
    protected function addHiddenAndDeleteFieldCheck(
        QueryBuilder $queryBuilder,
        string $fromAlias,
        string $table,
        string $joinAlias = 'e',
    ): QueryBuilder {
        $queryBuilder->leftJoin(
            $fromAlias,
            $table,
            $joinAlias,
            sprintf('%s.uid = %s.liked_uid', $joinAlias, $fromAlias),
        );

        // Respect delete and disabled fields
        if (isset($GLOBALS['TCA'][$table]['ctrl']['delete'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq(
                $joinAlias . '.' . $GLOBALS['TCA'][$table]['ctrl']['delete'],
                0,
            ));
        }

        if (isset($GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq(
                $joinAlias . '.' . $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'],
                0,
            ));
        }

        return $queryBuilder;
    }

    /**
     * Find a label for a record from $table with uid $uid.
     * This will only work if the table is registered in TCA!
     */
    protected function findLabelForTableItem(string $table, int $uid): string
    {
        $labelField = $GLOBALS['TCA'][$table]['ctrl']['label'];
        $row = $this
            ->getConnection()
            ->select(
                [$labelField],
                $table,
                ['uid' => $uid],
            )
            ->fetchAssociative();

        if (!isset($row[$labelField])) {
            throw new \UnexpectedValueException(
                'Could not find "' . $table . '" with uid "' . $uid . '".',
                1765460530,
            );
        }

        return (string)$row[$labelField];
    }

    public function countByRecord(string $likedTable, int $likedUid): int
    {
        return $this
            ->getConnection()
            ->count(
                '*',
                self::TABLE_NAME,
                [
                    'liked_table' => $likedTable,
                    'liked_uid' => $likedUid,
                ],
            );
    }

    /**
     * @return int number of affected rows
     */
    public function removeByRecord(LikeConfiguration $likeConfiguration): int
    {
        return $this
            ->getConnection()
            ->delete(
                self::TABLE_NAME,
                [
                    'liked_table' => $likeConfiguration->getTable(),
                    'liked_uid' => $likeConfiguration->getUid(),
                    'cookie_value' => $likeConfiguration->getCookieValue(),
                ],
            );
    }

    public function insertRecord(LikeConfiguration $likeConfiguration): int
    {
        $time = time();

        return $this
            ->getConnection()
            ->insert(
                self::TABLE_NAME,
                [
                    'liked_table' => $likeConfiguration->getTable(),
                    'liked_uid' => $likeConfiguration->getUid(),
                    'cookie_value' => $likeConfiguration->getCookieValue(),
                    'crdate' => $time,
                    'tstamp' => $time,
                ],
            );
    }

    protected function getConnection(): Connection
    {
        return $this->connectionPool->getConnectionForTable(self::TABLE_NAME);
    }
}
