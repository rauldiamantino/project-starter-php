<?php 

namespace App\Database\Interfaces;

interface ArticleContentRepositoryInterface
{
    public function existsByCompanyId(int $companyId): bool;
}
