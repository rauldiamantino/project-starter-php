<?php

namespace App\Database\Repositories;

use Core\Dbal\Repository;
use App\Database\Entities\ArticleEntity;
use InvalidArgumentException;
use RuntimeException;

class ArticleRepository extends Repository
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
            'is_active' => $entity->isActive,
            'title' => $entity->title,
            'slug' => $entity->slug,
            'user_id' => $entity->userId,
            'company_id' => $entity->companyId,
            'category_id' => $entity->categoryId,
            'viewsCount' => $entity->viewsCount,
            'ordering' => $entity->ordering,
        ];
    }

    public function getCategoryById(int $id): ArticleEntity
    {
        return $this->findById($id);
    }
}
