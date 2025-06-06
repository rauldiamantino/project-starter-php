<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606035639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add "slug" column to "categories" table and update unique index.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE categories ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_CATEGORIES_NAME_COMPANY_PARENT ON categories');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORIES_SLUG_NAME_COMPANY_PARENT ON categories (slug, name, company_id, parent_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE categories DROP COLUMN slug');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORIES_NAME_COMPANY_PARENT ON categories (name, company_id, parent_id)');
    }
}
