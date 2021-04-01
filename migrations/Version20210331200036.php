<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331200036 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76714819A0');
        $this->addSql('DROP INDEX IDX_D8698A76714819A0 ON document');
        $this->addSql('ALTER TABLE document CHANGE typeid type_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76714819A0 FOREIGN KEY (type_id_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76714819A0 ON document (type_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76714819A0');
        $this->addSql('DROP INDEX IDX_D8698A76714819A0 ON document');
        $this->addSql('ALTER TABLE document CHANGE type_id_id typeId INT NOT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76714819A0 FOREIGN KEY (typeId) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76714819A0 ON document (typeId)');
    }
}
