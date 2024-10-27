<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027061946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE expediteur_id_seq CASCADE');
        $this->addSql('DROP TABLE expediteur');
        $this->addSql('ALTER TABLE courrier ADD historique_courrier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE courrier ADD CONSTRAINT FK_BEF47CAA525A13A1 FOREIGN KEY (historique_courrier_id) REFERENCES historique_courrier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BEF47CAA525A13A1 ON courrier (historique_courrier_id)');
        $this->addSql('ALTER TABLE historique_courrier ADD destinataire JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE historique_courrier ADD date_envoie TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE historique_courrier ADD supprime BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE historique_courrier ALTER expediteur TYPE VARCHAR(50)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE expediteur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE expediteur (id INT NOT NULL, adresse VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE courrier DROP CONSTRAINT FK_BEF47CAA525A13A1');
        $this->addSql('DROP INDEX IDX_BEF47CAA525A13A1');
        $this->addSql('ALTER TABLE courrier DROP historique_courrier_id');
        $this->addSql('ALTER TABLE historique_courrier DROP destinataire');
        $this->addSql('ALTER TABLE historique_courrier DROP date_envoie');
        $this->addSql('ALTER TABLE historique_courrier DROP supprime');
        $this->addSql('ALTER TABLE historique_courrier ALTER expediteur TYPE JSON');
        $this->addSql('ALTER TABLE historique_courrier ALTER expediteur TYPE JSON');
    }
}
