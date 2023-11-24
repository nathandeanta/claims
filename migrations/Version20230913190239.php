<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230913190239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE debts ADD COLUMN ui_generate VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__debts AS SELECT id_debts, id_user, describe, service, amount, total, portion, number_of_installments, date, created_at FROM debts');
        $this->addSql('DROP TABLE debts');
        $this->addSql('CREATE TABLE debts (id_debts INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user INTEGER DEFAULT NULL, describe VARCHAR(255) DEFAULT NULL, service VARCHAR(255) DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, total NUMERIC(10, 2) DEFAULT NULL, portion INTEGER DEFAULT NULL, number_of_installments INTEGER DEFAULT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_6F64A29B6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO debts (id_debts, id_user, describe, service, amount, total, portion, number_of_installments, date, created_at) SELECT id_debts, id_user, describe, service, amount, total, portion, number_of_installments, date, created_at FROM __temp__debts');
        $this->addSql('DROP TABLE __temp__debts');
        $this->addSql('CREATE INDEX IDX_6F64A29B6B3CA4B ON debts (id_user)');
    }
}
