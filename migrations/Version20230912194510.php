<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912194510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD COLUMN position VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id_user, name, email, password, admin, active FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id_user INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, admin INTEGER NOT NULL, active INTEGER NOT NULL)');
        $this->addSql('INSERT INTO user (id_user, name, email, password, admin, active) SELECT id_user, name, email, password, admin, active FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
