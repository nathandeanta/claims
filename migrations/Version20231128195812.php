<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128195812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE theft (id_theft INT AUTO_INCREMENT NOT NULL, id_policy INT DEFAULT NULL, `desc` VARCHAR(255) NOT NULL, date_occurrence DATETIME NOT NULL, INDEX IDX_1E311E31D64AB0FB (id_policy), PRIMARY KEY(id_theft)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE theft ADD CONSTRAINT FK_1E311E31D64AB0FB FOREIGN KEY (id_policy) REFERENCES policy (id_policy)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theft DROP FOREIGN KEY FK_1E311E31D64AB0FB');
        $this->addSql('DROP TABLE theft');
    }
}
