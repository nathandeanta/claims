<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231125134113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id_address INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, description VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, INDEX IDX_D4E6F81E173B1B8 (id_client), PRIMARY KEY(id_address)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id_city INT NOT NULL, name VARCHAR(255) NOT NULL, id_state INT DEFAULT NULL, ddd INT NOT NULL, PRIMARY KEY(id_city)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id_client INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, document VARCHAR(255) NOT NULL, rg VARCHAR(255) NOT NULL, date_of_birth DATE NOT NULL, password VARCHAR(255) NOT NULL, active VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, PRIMARY KEY(id_client)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE policy (id_policy INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, number VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, INDEX IDX_F07D0516E173B1B8 (id_client), PRIMARY KEY(id_policy)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roofing (id_roofing INT AUTO_INCREMENT NOT NULL, id_policy INT DEFAULT NULL, id_type_roofing INT DEFAULT NULL, created DATETIME DEFAULT NULL, INDEX IDX_75B8A9CDD64AB0FB (id_policy), INDEX IDX_75B8A9CD7E58B34A (id_type_roofing), PRIMARY KEY(id_roofing)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id_state INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) NOT NULL, id_capital INT NOT NULL, PRIMARY KEY(id_state)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_roofing (id_type_roofing INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, PRIMARY KEY(id_type_roofing)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id_user INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, admin INT NOT NULL, active INT NOT NULL, PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81E173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
        $this->addSql('ALTER TABLE policy ADD CONSTRAINT FK_F07D0516E173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
        $this->addSql('ALTER TABLE roofing ADD CONSTRAINT FK_75B8A9CDD64AB0FB FOREIGN KEY (id_policy) REFERENCES policy (id_policy)');
        $this->addSql('ALTER TABLE roofing ADD CONSTRAINT FK_75B8A9CD7E58B34A FOREIGN KEY (id_type_roofing) REFERENCES type_roofing (id_type_roofing)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81E173B1B8');
        $this->addSql('ALTER TABLE policy DROP FOREIGN KEY FK_F07D0516E173B1B8');
        $this->addSql('ALTER TABLE roofing DROP FOREIGN KEY FK_75B8A9CDD64AB0FB');
        $this->addSql('ALTER TABLE roofing DROP FOREIGN KEY FK_75B8A9CD7E58B34A');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE policy');
        $this->addSql('DROP TABLE roofing');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE type_roofing');
        $this->addSql('DROP TABLE user');
    }
}
