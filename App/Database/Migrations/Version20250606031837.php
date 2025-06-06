<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606031837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the articles table (metadata only).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE articles (
            id INT AUTO_INCREMENT NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            user_id INT NOT NULL,
            company_id INT NOT NULL,
            category_id INT NOT NULL,
            views_count INT DEFAULT 0 NOT NULL,
            ordering INT DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id),
            CONSTRAINT FK_ARTICLES_USER FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
            CONSTRAINT FK_ARTICLES_COMPANY FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE RESTRICT,
            CONSTRAINT FK_ARTICLES_CATEGORY FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE INDEX IDX_ARTICLES_CATEGORY ON articles (category_id)');
        $this->addSql('CREATE INDEX IDX_ARTICLES_USER ON articles (user_id)');
        $this->addSql('CREATE INDEX IDX_ARTICLES_COMPANY ON articles (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ARTICLES_SLUG ON articles (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE articles');
    }
}
