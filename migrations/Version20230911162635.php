<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911162635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE investment_entity ADD COLUMN bank VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE investment_entity ADD COLUMN description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__investment_entity AS SELECT id_investment, amount, tax, date, created_at FROM investment_entity');
        $this->addSql('DROP TABLE investment_entity');
        $this->addSql('CREATE TABLE investment_entity (id_investment INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, tax NUMERIC(10, 2) NOT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO investment_entity (id_investment, amount, tax, date, created_at) SELECT id_investment, amount, tax, date, created_at FROM __temp__investment_entity');
        $this->addSql('DROP TABLE __temp__investment_entity');
    }
}
