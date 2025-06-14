<?php

namespace App\Database\Repositories\Implementations\Doctrine;

use RuntimeException;
use InvalidArgumentException;
use App\Database\Entities\ArticleEntity;
use App\Database\Repositories\Interfaces\ArticleRepositoryInterface;
use Core\Database\Implementations\Doctrine\AbstractRepositoryDoctrine;

class ArticleRepositoryDoctrine extends AbstractRepositoryDoctrine implements ArticleRepositoryInterface
{
    protected string $table = 'categories';

    protected function createEntityFromData(array $data): ArticleEntity
    {
        try {
            $companyEntity = ArticleEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Invalid data from database for ArticleEntity: ' . $e->getMessage(), ['data' => $data]);
            throw new RuntimeException('Internal failure: Corrupted data received from database for ArticleEntity.', 0, $e);
        }

        return $companyEntity;
    }

    protected function mapEntityToData(object $entity): array
    {
        /** @var ArticleEntity $entity */
        return [
            'is_active' => $entity->getIsActive(),
            'title' => $entity->getTitle(),
            'slug' => $entity->getSlug(),
            'user_id' => $entity->getUserId(),
            'company_id' => $entity->getCompanyId(),
            'category_id' => $entity->getCategoryId(),
            'viewsCount' => $entity->getViewsCount(),
            'ordering' => $entity->getOrdering(),
        ];
    }
}
