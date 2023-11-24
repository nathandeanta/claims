<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230913164924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE debts (id_debts INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, describe VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, portion INTEGER NOT NULL, number_of_installments INTEGER NOT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('ALTER TABLE total ADD COLUMN service VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE total ADD COLUMN date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE debts');
        $this->addSql('CREATE TEMPORARY TABLE __temp__total AS SELECT id_total, amount FROM total');
        $this->addSql('DROP TABLE total');
        $this->addSql('CREATE TABLE total (id_total INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL)');
        $this->addSql('INSERT INTO total (id_total, amount) SELECT id_total, amount FROM __temp__total');
        $this->addSql('DROP TABLE __temp__total');
    }
}
