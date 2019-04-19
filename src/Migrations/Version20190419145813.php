<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @package DoctrineMigrations
 */
final class Version20190419145813 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE Users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE Users (id INT NOT NULL, name VARCHAR(255) NOT NULL, birthday TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, locale VARCHAR(4) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE "user"');
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE Users_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(255) NOT NULL, birthday TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, locale VARCHAR(4) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE Users');
    }
}
