<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606031906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the article_contents table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE article_contents (
            id INT AUTO_INCREMENT NOT NULL,
            article_id INT NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            type VARCHAR(255) NOT NULL,
            title VARCHAR(255) NOT NULL,
            hide_title TINYINT(1) NOT NULL DEFAULT 0,
            content LONGTEXT NOT NULL,
            ordering INT DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id),
            CONSTRAINT FK_ARTICLE_CONTENTS_ARTICLE FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE INDEX IDX_ARTICLE_CONTENTS_ARTICLE ON article_contents (article_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE article_contents');
    }
}
