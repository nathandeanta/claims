<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230916092510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_flow ADD COLUMN title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_flow AS SELECT id_type_flow FROM type_flow');
        $this->addSql('DROP TABLE type_flow');
        $this->addSql('CREATE TABLE type_flow (id_type_flow INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO type_flow (id_type_flow) SELECT id_type_flow FROM __temp__type_flow');
        $this->addSql('DROP TABLE __temp__type_flow');
    }
}
