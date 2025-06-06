<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606030743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the companies table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE companies (
            id INT AUTO_INCREMENT NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            cnpj VARCHAR(14) NOT NULL UNIQUE,
            slug VARCHAR(255) NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_COMPANIES_CNPJ ON companies (cnpj)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_COMPANIES_SLUG ON companies (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE companies');
    }
}
