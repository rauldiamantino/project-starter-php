<?php

namespace App\Database\Repositories;

use PDOException;
use RuntimeException;
use Core\Dbal\Repository;
use InvalidArgumentException;
use Doctrine\DBAL\Exception as DBALException;
use App\Database\Entities\ArticleContentEntity;

class ArticleContentRepository extends Repository
{
    protected string $table = 'article_contents';

    protected function createEntityFromData(array $data): ArticleContentEntity
    {
        try {
            $companyEntity = ArticleContentEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Invalid data from database for ArticleContentEntity: ' . $e->getMessage(), ['data' => $data]);
            throw new RuntimeException('Internal failure: Corrupted data received from database for ArticleContentEntity.', 0, $e);
        }

        return $companyEntity;
    }

    protected function mapEntityToData(object $entity): array
    {
        /** @var ArticleContentEntity $entity */
        return [
            'isActive' => $entity->isActive,
            'articleId' => $entity->articleId,
            'type' => $entity->type,
            'title' => $entity->title,
            'hideTitle' => $entity->hideTitle,
            'content' => $entity->content,
            'ordering' => $entity->ordering,
        ];
    }

    public function existsByCompanyId(int $companyId): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $count = $queryBuilder->select('COUNT(ArticleContent.id)')
                ->from('article_contents', 'ArticleContent')
                ->innerJoin(
                    'ArticleContent',
                    'articles',
                    'Article',
                    'ArticleContent.article_id = Article.id'
                )
                ->where('Article.company_id = :companyId')
                ->setParameter('companyId', $companyId)
                ->fetchOne();

            return (int) $count > 0;
        } catch (DBALException $e) {
            $this->logger->error(
                'DBAL Error in ArticleContentsRepository::existsByCompanyId: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'companyId' => $companyId,
                ]
            );

            throw new RuntimeException('Database error while checking company dependents', 0, $e);
        } catch (PDOException $e) {
            $this->logger->error(
                'PDO Error in ArticleContentsRepository::existsByCompanyId: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'companyId' => $companyId,
                ]
            );

            throw new RuntimeException('Database connection error when checking company dependents', 0, $e);
        }
    }
}
