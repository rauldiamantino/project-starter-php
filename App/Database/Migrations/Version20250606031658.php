<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606031658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the users table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
            id INT AUTO_INCREMENT NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            level VARCHAR(50) NOT NULL,
            company_id INT DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id),
            CONSTRAINT FK_USERS_COMPANY FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_EMAIL ON users (email)');
        $this->addSql('CREATE INDEX IDX_USERS_COMPANY ON users (company_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
