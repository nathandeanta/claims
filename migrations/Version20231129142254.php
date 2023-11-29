<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129142254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE password (id_password INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, code VARCHAR(255) NOT NULL, expired DATETIME NOT NULL, created DATETIME NOT NULL, submit DATETIME NOT NULL, INDEX IDX_35C246D5E173B1B8 (id_client), PRIMARY KEY(id_password)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE password ADD CONSTRAINT FK_35C246D5E173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE password DROP FOREIGN KEY FK_35C246D5E173B1B8');
        $this->addSql('DROP TABLE password');
    }
}
