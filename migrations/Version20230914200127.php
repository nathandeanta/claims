<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230914200127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__cash_flow AS SELECT id_cash_flow, value, description, type, date, created_at, type_transactiion, bank, currency, currency_to, current_convert, merchant FROM cash_flow');
        $this->addSql('DROP TABLE cash_flow');
        $this->addSql('CREATE TABLE cash_flow (id_cash_flow INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_type_flow INTEGER DEFAULT NULL, value NUMERIC(10, 2) NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL, type_transactiion VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, currency_to VARCHAR(255) DEFAULT NULL, current_convert NUMERIC(10, 2) DEFAULT NULL, merchant VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_6F461F1D5FAAF5F FOREIGN KEY (id_type_flow) REFERENCES type_flow (id_type_flow) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cash_flow (id_cash_flow, value, description, type, date, created_at, type_transactiion, bank, currency, currency_to, current_convert, merchant) SELECT id_cash_flow, value, description, type, date, created_at, type_transactiion, bank, currency, currency_to, current_convert, merchant FROM __temp__cash_flow');
        $this->addSql('DROP TABLE __temp__cash_flow');
        $this->addSql('CREATE INDEX IDX_6F461F1D5FAAF5F ON cash_flow (id_type_flow)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__debts AS SELECT id_debts, id_user, describe, amount, portion, number_of_installments, date, created_at, service, total, ui_generate FROM debts');
        $this->addSql('DROP TABLE debts');
        $this->addSql('CREATE TABLE debts (id_debts INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user INTEGER DEFAULT NULL, id_type_flow INTEGER DEFAULT NULL, describe VARCHAR(255) DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, portion INTEGER DEFAULT NULL, number_of_installments INTEGER DEFAULT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, service VARCHAR(255) DEFAULT NULL, total NUMERIC(10, 2) DEFAULT NULL, ui_generate VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_6F64A29B6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6F64A29B5FAAF5F FOREIGN KEY (id_type_flow) REFERENCES type_flow (id_type_flow) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO debts (id_debts, id_user, describe, amount, portion, number_of_installments, date, created_at, service, total, ui_generate) SELECT id_debts, id_user, describe, amount, portion, number_of_installments, date, created_at, service, total, ui_generate FROM __temp__debts');
        $this->addSql('DROP TABLE __temp__debts');
        $this->addSql('CREATE INDEX IDX_6F64A29B6B3CA4B ON debts (id_user)');
        $this->addSql('CREATE INDEX IDX_6F64A29B5FAAF5F ON debts (id_type_flow)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__cash_flow AS SELECT id_cash_flow, value, description, type, type_transactiion, bank, currency, currency_to, current_convert, merchant, date, created_at FROM cash_flow');
        $this->addSql('DROP TABLE cash_flow');
        $this->addSql('CREATE TABLE cash_flow (id_cash_flow INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, value NUMERIC(10, 2) NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, type_transactiion VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, currency_to VARCHAR(255) DEFAULT NULL, current_convert NUMERIC(10, 2) DEFAULT NULL, merchant VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO cash_flow (id_cash_flow, value, description, type, type_transactiion, bank, currency, currency_to, current_convert, merchant, date, created_at) SELECT id_cash_flow, value, description, type, type_transactiion, bank, currency, currency_to, current_convert, merchant, date, created_at FROM __temp__cash_flow');
        $this->addSql('DROP TABLE __temp__cash_flow');
        $this->addSql('CREATE TEMPORARY TABLE __temp__debts AS SELECT id_debts, id_user, describe, ui_generate, service, amount, total, portion, number_of_installments, date, created_at FROM debts');
        $this->addSql('DROP TABLE debts');
        $this->addSql('CREATE TABLE debts (id_debts INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user INTEGER DEFAULT NULL, describe VARCHAR(255) DEFAULT NULL, ui_generate VARCHAR(255) DEFAULT NULL, service VARCHAR(255) DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, total NUMERIC(10, 2) DEFAULT NULL, portion INTEGER DEFAULT NULL, number_of_installments INTEGER DEFAULT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_6F64A29B6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO debts (id_debts, id_user, describe, ui_generate, service, amount, total, portion, number_of_installments, date, created_at) SELECT id_debts, id_user, describe, ui_generate, service, amount, total, portion, number_of_installments, date, created_at FROM __temp__debts');
        $this->addSql('DROP TABLE __temp__debts');
        $this->addSql('CREATE INDEX IDX_6F64A29B6B3CA4B ON debts (id_user)');
    }
}
