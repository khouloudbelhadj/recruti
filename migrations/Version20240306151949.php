<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306151949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous ADD relation_id INT NOT NULL, CHANGE offer_id offer_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA83256915B FOREIGN KEY (relation_id) REFERENCES condidature (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C09A9BA83256915B ON rendezvous (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA83256915B');
        $this->addSql('DROP INDEX UNIQ_C09A9BA83256915B ON rendezvous');
        $this->addSql('ALTER TABLE rendezvous DROP relation_id, CHANGE offer_id offer_id INT DEFAULT NULL');
    }
}
