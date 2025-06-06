<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606034309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changes the type of "level" column in "users" table from VARCHAR to TINYINT.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users CHANGE level level TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users CHANGE level level VARCHAR(50) NOT NULL');
    }
}
