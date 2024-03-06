<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306153924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condidature DROP FOREIGN KEY FK_FDF2E30B53C674EE');
        $this->addSql('DROP INDEX IDX_FDF2E30B53C674EE ON condidature');
        $this->addSql('ALTER TABLE condidature DROP offer_id');
        $this->addSql('ALTER TABLE rendezvous CHANGE offer_id offer_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condidature ADD offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE condidature ADD CONSTRAINT FK_FDF2E30B53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_FDF2E30B53C674EE ON condidature (offer_id)');
        $this->addSql('ALTER TABLE rendezvous CHANGE offer_id offer_id INT DEFAULT NULL');
    }
}
