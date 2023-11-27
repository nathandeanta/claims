<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127223533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE policy ADD id_model_phone INT DEFAULT NULL, ADD id_color INT DEFAULT NULL, ADD id_capacity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE policy ADD CONSTRAINT FK_F07D0516621E7ADD FOREIGN KEY (id_model_phone) REFERENCES model_phone (id_model_phone)');
        $this->addSql('ALTER TABLE policy ADD CONSTRAINT FK_F07D051688D309D9 FOREIGN KEY (id_color) REFERENCES color (id_color)');
        $this->addSql('ALTER TABLE policy ADD CONSTRAINT FK_F07D0516E5335DE1 FOREIGN KEY (id_capacity) REFERENCES capacity (id_capacity)');
        $this->addSql('CREATE INDEX IDX_F07D0516621E7ADD ON policy (id_model_phone)');
        $this->addSql('CREATE INDEX IDX_F07D051688D309D9 ON policy (id_color)');
        $this->addSql('CREATE INDEX IDX_F07D0516E5335DE1 ON policy (id_capacity)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE policy DROP FOREIGN KEY FK_F07D0516621E7ADD');
        $this->addSql('ALTER TABLE policy DROP FOREIGN KEY FK_F07D051688D309D9');
        $this->addSql('ALTER TABLE policy DROP FOREIGN KEY FK_F07D0516E5335DE1');
        $this->addSql('DROP INDEX IDX_F07D0516621E7ADD ON policy');
        $this->addSql('DROP INDEX IDX_F07D051688D309D9 ON policy');
        $this->addSql('DROP INDEX IDX_F07D0516E5335DE1 ON policy');
        $this->addSql('ALTER TABLE policy DROP id_model_phone, DROP id_color, DROP id_capacity');
    }
}
