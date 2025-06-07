<?php

namespace App\Database\Repositories;

use Core\Dbal\Repository;
use App\Database\Entities\CategoryEntity;
use PDOException;
use InvalidArgumentException;
use RuntimeException;
use Doctrine\DBAL\Exception as DBALException;

class CategoryRepository extends Repository
{
    protected string $table = 'categories';

    protected function createEntityFromData(array $data): CategoryEntity
    {
        try {
            $companyEntity = CategoryEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Invalid data from database for CategoryEntity: ' . $e->getMessage(), ['data' => $data]);
            throw new RuntimeException('Internal failure: Corrupted data received from database for CategoryEntity.', 0, $e);
        }

        return $companyEntity;
    }

    protected function mapEntityToData(object $entity): array
    {
        /** @var CategoryEntity $entity */
        return [
            'is_active' => $entity->getIsActive(),
            'parent_id' => $entity->getParentId(),
            'name' => $entity->getName(),
            'slug' => $entity->getSlug(),
            'description' => $entity->getDescription(),
            'ordering' => $entity->getOrdering(),
            'company_id' => $entity->getCompanyId(),
        ];
    }

    public function getCategoryById(int $id): CategoryEntity
    {
        return $this->findById($id);
    }
}
