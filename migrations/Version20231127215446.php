<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127215446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roofing DROP FOREIGN KEY FK_75B8A9CD7E58B34A');
        $this->addSql('ALTER TABLE roofing DROP FOREIGN KEY FK_75B8A9CDD64AB0FB');
        $this->addSql('DROP TABLE roofing');
        $this->addSql('ALTER TABLE policy ADD id_type_roofing INT DEFAULT NULL');
        $this->addSql('ALTER TABLE policy ADD CONSTRAINT FK_F07D05167E58B34A FOREIGN KEY (id_type_roofing) REFERENCES type_roofing (id_type_roofing)');
        $this->addSql('CREATE INDEX IDX_F07D05167E58B34A ON policy (id_type_roofing)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roofing (id_roofing INT AUTO_INCREMENT NOT NULL, id_policy INT DEFAULT NULL, id_type_roofing INT DEFAULT NULL, created DATETIME DEFAULT NULL, INDEX IDX_75B8A9CDD64AB0FB (id_policy), INDEX IDX_75B8A9CD7E58B34A (id_type_roofing), PRIMARY KEY(id_roofing)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE roofing ADD CONSTRAINT FK_75B8A9CD7E58B34A FOREIGN KEY (id_type_roofing) REFERENCES type_roofing (id_type_roofing)');
        $this->addSql('ALTER TABLE roofing ADD CONSTRAINT FK_75B8A9CDD64AB0FB FOREIGN KEY (id_policy) REFERENCES policy (id_policy)');
        $this->addSql('ALTER TABLE policy DROP FOREIGN KEY FK_F07D05167E58B34A');
        $this->addSql('DROP INDEX IDX_F07D05167E58B34A ON policy');
        $this->addSql('ALTER TABLE policy DROP id_type_roofing');
    }
}
