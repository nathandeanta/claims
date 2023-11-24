<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230916093041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_flow AS SELECT id_type_flow, title FROM type_flow');
        $this->addSql('DROP TABLE type_flow');
        $this->addSql('CREATE TABLE type_flow (id_type_flow INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, CONSTRAINT FK_85CF13786B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO type_flow (id_type_flow, title) SELECT id_type_flow, title FROM __temp__type_flow');
        $this->addSql('DROP TABLE __temp__type_flow');
        $this->addSql('CREATE INDEX IDX_85CF13786B3CA4B ON type_flow (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_flow AS SELECT id_type_flow, title FROM type_flow');
        $this->addSql('DROP TABLE type_flow');
        $this->addSql('CREATE TABLE type_flow (id_type_flow INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO type_flow (id_type_flow, title) SELECT id_type_flow, title FROM __temp__type_flow');
        $this->addSql('DROP TABLE __temp__type_flow');
    }
}
