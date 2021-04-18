<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210418201707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add deathday column for user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD deathday TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE Users DROP deathday');
    }
}
