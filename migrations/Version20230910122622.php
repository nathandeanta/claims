<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910122622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__cash_flow AS SELECT id_cash_flow, value, description, type, date, created_at, type_transactiion, bank, currency FROM cash_flow');
        $this->addSql('DROP TABLE cash_flow');
        $this->addSql('CREATE TABLE cash_flow (id_cash_flow INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, value NUMERIC(10, 2) NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL, type_transactiion VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, currency_to VARCHAR(255) DEFAULT NULL, current_convert NUMERIC(10, 2) DEFAULT NULL, merchant VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO cash_flow (id_cash_flow, value, description, type, date, created_at, type_transactiion, bank, currency) SELECT id_cash_flow, value, description, type, date, created_at, type_transactiion, bank, currency FROM __temp__cash_flow');
        $this->addSql('DROP TABLE __temp__cash_flow');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__cash_flow AS SELECT id_cash_flow, value, description, type, type_transactiion, bank, currency, date, created_at FROM cash_flow');
        $this->addSql('DROP TABLE cash_flow');
        $this->addSql('CREATE TABLE cash_flow (id_cash_flow INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, value NUMERIC(10, 2) NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, type_transactiion VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO cash_flow (id_cash_flow, value, description, type, type_transactiion, bank, currency, date, created_at) SELECT id_cash_flow, value, description, type, type_transactiion, bank, currency, date, created_at FROM __temp__cash_flow');
        $this->addSql('DROP TABLE __temp__cash_flow');
    }
}
