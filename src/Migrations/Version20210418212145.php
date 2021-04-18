<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210418212145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add gender column for user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD gender VARCHAR(6) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE Users DROP gender');
    }
}
