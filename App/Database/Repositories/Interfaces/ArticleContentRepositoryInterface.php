<?php

namespace App\Database\Repositories\Interfaces;

interface ArticleContentRepositoryInterface
{
    public function existsByCompanyId(int $companyId): bool;
}
