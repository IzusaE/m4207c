<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401092559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access ADD utilisateur_id_id INT NOT NULL, ADD autorisation_id_id INT NOT NULL, ADD document_id_id INT NOT NULL, DROP utilisateur_id, DROP autorisation_id, DROP document_id');
        $this->addSql('ALTER TABLE access ADD CONSTRAINT FK_6692B54B981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE access ADD CONSTRAINT FK_6692B543B0E139B FOREIGN KEY (autorisation_id_id) REFERENCES autorisation (id)');
        $this->addSql('ALTER TABLE access ADD CONSTRAINT FK_6692B5416E5E825 FOREIGN KEY (document_id_id) REFERENCES document (id)');
        $this->addSql('CREATE INDEX IDX_6692B54B981C689 ON access (utilisateur_id_id)');
        $this->addSql('CREATE INDEX IDX_6692B543B0E139B ON access (autorisation_id_id)');
        $this->addSql('CREATE INDEX IDX_6692B5416E5E825 ON access (document_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access DROP FOREIGN KEY FK_6692B54B981C689');
        $this->addSql('ALTER TABLE access DROP FOREIGN KEY FK_6692B543B0E139B');
        $this->addSql('ALTER TABLE access DROP FOREIGN KEY FK_6692B5416E5E825');
        $this->addSql('DROP INDEX IDX_6692B54B981C689 ON access');
        $this->addSql('DROP INDEX IDX_6692B543B0E139B ON access');
        $this->addSql('DROP INDEX IDX_6692B5416E5E825 ON access');
        $this->addSql('ALTER TABLE access ADD utilisateur_id INT NOT NULL, ADD autorisation_id INT NOT NULL, ADD document_id INT NOT NULL, DROP utilisateur_id_id, DROP autorisation_id_id, DROP document_id_id');
    }
}
