<?php 

namespace App\Database\Interfaces;

use App\Database\Entities\CategoryEntity;

interface CategoryRepositoryInterface
{
    public function getCategoryById(int $id): CategoryEntity;
}