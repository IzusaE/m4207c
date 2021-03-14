<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309102240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access (id INT AUTO_INCREMENT NOT NULL, utilisateur_id_id_id INT NOT NULL, autorisation_id_id_id INT NOT NULL, document_id_id_id INT NOT NULL, INDEX IDX_6692B548FBA26F8 (utilisateur_id_id_id), INDEX IDX_6692B541150FE8F (autorisation_id_id_id), INDEX IDX_6692B541E002E3E (document_id_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE autorisation (id INT AUTO_INCREMENT NOT NULL, lecture SMALLINT NOT NULL, ecriture SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type_id_id_id INT NOT NULL, chemin VARCHAR(255) NOT NULL, date DATETIME NOT NULL, actif SMALLINT NOT NULL, INDEX IDX_D8698A765C3EF89B (type_id_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, groupe_id_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE access ADD CONSTRAINT FK_6692B548FBA26F8 FOREIGN KEY (utilisateur_id_id_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE access ADD CONSTRAINT FK_6692B541150FE8F FOREIGN KEY (autorisation_id_id_id) REFERENCES autorisation (id)');
        $this->addSql('ALTER TABLE access ADD CONSTRAINT FK_6692B541E002E3E FOREIGN KEY (document_id_id_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A765C3EF89B FOREIGN KEY (type_id_id_id) REFERENCES genre (id)');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access DROP FOREIGN KEY FK_6692B541150FE8F');
        $this->addSql('ALTER TABLE access DROP FOREIGN KEY FK_6692B541E002E3E');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A765C3EF89B');
        $this->addSql('ALTER TABLE access DROP FOREIGN KEY FK_6692B548FBA26F8');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE access');
        $this->addSql('DROP TABLE autorisation');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE utilisateur');
    }
}
