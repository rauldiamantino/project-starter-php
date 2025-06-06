<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606031753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the categories table with parent_id for subcategories.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE categories (
            id INT AUTO_INCREMENT NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            parent_id INT DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            company_id INT DEFAULT NULL,
            ordering INT DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id),
            CONSTRAINT FK_CATEGORIES_PARENT FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
            CONSTRAINT FK_CATEGORIES_COMPANY FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE INDEX IDX_CATEGORIES_PARENT ON categories (parent_id)');
        $this->addSql('CREATE INDEX IDX_CATEGORIES_COMPANY ON categories (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORIES_SLUG_NAME_COMPANY_PARENT ON categories (slug, name, company_id, parent_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE categories');
    }
}
