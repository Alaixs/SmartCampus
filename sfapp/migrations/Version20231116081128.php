<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231116081128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room ADD id_sa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B8C4FA076 FOREIGN KEY (id_sa_id) REFERENCES acquisition_unit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_729F519B8C4FA076 ON room (id_sa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B8C4FA076');
        $this->addSql('DROP INDEX UNIQ_729F519B8C4FA076 ON room');
        $this->addSql('ALTER TABLE room DROP id_sa_id');
    }
}
