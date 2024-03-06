<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306152753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous ADD condidature_id INT NOT NULL, CHANGE offer_id offer_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA84CF41933 FOREIGN KEY (condidature_id) REFERENCES condidature (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C09A9BA84CF41933 ON rendezvous (condidature_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA84CF41933');
        $this->addSql('DROP INDEX UNIQ_C09A9BA84CF41933 ON rendezvous');
        $this->addSql('ALTER TABLE rendezvous DROP condidature_id, CHANGE offer_id offer_id INT DEFAULT NULL');
    }
}
