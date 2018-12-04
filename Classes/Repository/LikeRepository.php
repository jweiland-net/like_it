<?php
declare(strict_types=1);
namespace JWeiland\LikeIt\Repository;

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

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Repository to add, remove or check for existing like records
 */
class LikeRepository
{
    protected const TABLE_NAME = 'tx_likeit_like';

    /**
     * @param string $likedTable
     * @param int $likedUid
     * @param string $cookieValue
     * @return array|null
     */
    public function findByRecord(string $likedTable, int $likedUid, string $cookieValue)
    {
        return $this
            ->getConnection()
            ->select(
                ['*'],
                self::TABLE_NAME,
                [
                    'liked_table' => $likedTable,
                    'liked_uid' => $likedUid,
                    'cookie_value' => $cookieValue
                ]
            )
            ->fetch();
    }

    /**
     * Find all installed liked tables
     * e.g. ['tx_news_domain_model_news', 'tt_content']
     *
     * @return array
     */
    public function findAllInstalledLikedTables(): array
    {
        $rows = $this
            ->getConnection()
            ->select(
                ['liked_table'],
                self::TABLE_NAME,
                [],
                ['liked_table']
            )
            ->fetchAll();
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
     * e.g. findLikedTableItems('tx_news_domain_model_news')
     * will return sth. like: '[1 => ['label' => 'Hello World', 'amount' => 512], 23 => 'label' => 'My new garden', 'amount' => 23]]
     *
     * @param string $table
     * @return array
     */
    public function findLikedTableItems(string $table): array
    {
        if (!isset($GLOBALS['TCA'][$table])) {
            throw new \UnexpectedValueException(
                'Could not find "' . $table . '" in TCA!',
                1543591232519
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
        $rows = $queryBuilder->execute()->fetchAll();
        $items = [];
        foreach ($rows as $row) {
            $items[$row['liked_uid']] = [
                'label' => $this->findLabelForTableItem($table, (int)$row['liked_uid']),
                'amount' => $row['amount']
            ];
        }
        return $items;
    }

    /**
     * Add hidden and delete field checks extracted from TCA
     * to the current QueryBuilder instance.
     * You need to check if $GLOBALS['TCA'][$table] exists!
     *
     * @param QueryBuilder $queryBuilder
     * @param string $fromAlias
     * @param string $table
     * @param string $joinAlias default: e (= external)
     * @return QueryBuilder
     */
    protected function addHiddenAndDeleteFieldCheck(
        QueryBuilder $queryBuilder,
        string $fromAlias,
        string $table,
        string $joinAlias = 'e'
    ): QueryBuilder
    {
        $queryBuilder->leftJoin(
            $fromAlias,
            $table,
            $joinAlias,
            sprintf('%s.uid = %s.liked_uid', $joinAlias, $fromAlias)
        );
        // Respect delete and disabled fields
        if (isset($GLOBALS['TCA'][$table]['ctrl']['delete'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq(
                $joinAlias . '.' . $GLOBALS['TCA'][$table]['ctrl']['delete'],
                0
            ));
        }
        if (isset($GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq(
                $joinAlias . '.' . $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'],
                0
            ));
        }
        return $queryBuilder;
    }

    /**
     * Find label for a record from $table with uid $uid.
     * This will only work if the table is registered in TCA!
     *
     * @param string $table
     * @param int $uid
     * @return string
     */
    protected function findLabelForTableItem(string $table, int $uid): string
    {
        $labelField = $GLOBALS['TCA'][$table]['ctrl']['label'];
        $row = $this
            ->getConnection()
            ->select(
                [$labelField],
                $table,
                ['uid' => $uid]
            )
            ->fetch();
        if (!isset($row[$labelField])) {
            throw new \UnexpectedValueException(
                'Could not find "' . $table . '" with uid "' . $uid . '".',
                1543591329803
            );
        }
        return (string)$row[$labelField];
    }

    /**
     * @param string $likedTable
     * @param int $likedUid
     * @return int
     */
    public function countByRecord(string $likedTable, int $likedUid): int
    {
        return $this
            ->getConnection()
            ->count('*', self::TABLE_NAME, ['liked_table' => $likedTable, 'liked_uid' => $likedUid]);
    }

    /**
     * @param string $likedTable
     * @param int $likedUid
     * @param string $cookieValue
     * @return int number of affected rows
     */
    public function removeByRecord(string $likedTable, int $likedUid, string $cookieValue): int
    {
        return $this
            ->getConnection()
            ->delete(
                self::TABLE_NAME,
                [
                    'liked_table' => $likedTable,
                    'liked_uid' => $likedUid,
                    'cookie_value' => $cookieValue
                ]
            );
    }

    /**
     * @param string $likedTable
     * @param int $likedUid
     * @param string $cookieValue
     * @return int number of affected rows
     */
    public function insertRecord(string $likedTable, int $likedUid, string $cookieValue): int
    {
        $time = time();
        return $this
            ->getConnection()
            ->insert(
                self::TABLE_NAME,
                [
                    'liked_table' => $likedTable,
                    'liked_uid' => $likedUid,
                    'cookie_value' => $cookieValue,
                    'crdate' => $time,
                    'tstamp' => $time
                ]
            );
    }

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::TABLE_NAME);
    }
}
