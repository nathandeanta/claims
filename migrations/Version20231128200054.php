<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128200054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theft DROP INDEX IDX_1E311E31D64AB0FB, ADD UNIQUE INDEX UNIQ_1E311E31D64AB0FB (id_policy)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theft DROP INDEX UNIQ_1E311E31D64AB0FB, ADD INDEX IDX_1E311E31D64AB0FB (id_policy)');
    }
}
