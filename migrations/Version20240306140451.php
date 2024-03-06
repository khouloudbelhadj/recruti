<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306140451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA853C674EE');
        $this->addSql('DROP INDEX IDX_C09A9BA853C674EE ON rendezvous');
        $this->addSql('ALTER TABLE rendezvous ADD many_to_one_id INT NOT NULL, DROP offer_id');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA8EAB5DEB FOREIGN KEY (many_to_one_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_C09A9BA8EAB5DEB ON rendezvous (many_to_one_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA8EAB5DEB');
        $this->addSql('DROP INDEX IDX_C09A9BA8EAB5DEB ON rendezvous');
        $this->addSql('ALTER TABLE rendezvous ADD offer_id INT DEFAULT NULL, DROP many_to_one_id');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA853C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_C09A9BA853C674EE ON rendezvous (offer_id)');
    }
}
