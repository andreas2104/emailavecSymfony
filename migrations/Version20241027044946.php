<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027044946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE departement_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE expediteur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE courrier (id INT NOT NULL, expediteur_id INT NOT NULL, type VARCHAR(255) NOT NULL, date_envoi DATE NOT NULL, date_reception DATE DEFAULT NULL, piece_jointe VARCHAR(255) DEFAULT NULL, objet TEXT NOT NULL, exped_courrier VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEF47CAA10335F61 ON courrier (expediteur_id)');
        $this->addSql('CREATE TABLE courrier_user (courrier_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(courrier_id, user_id))');
        $this->addSql('CREATE INDEX IDX_343667B8BF41DC7 ON courrier_user (courrier_id)');
        $this->addSql('CREATE INDEX IDX_343667BA76ED395 ON courrier_user (user_id)');
        $this->addSql('CREATE TABLE expediteur (id INT NOT NULL, adresse VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE courrier ADD CONSTRAINT FK_BEF47CAA10335F61 FOREIGN KEY (expediteur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE courrier_user ADD CONSTRAINT FK_343667B8BF41DC7 FOREIGN KEY (courrier_id) REFERENCES courrier (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE courrier_user ADD CONSTRAINT FK_343667BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX idx_8d93d6498bf41dc7');
        $this->addSql('ALTER TABLE "user" DROP courrier_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE expediteur_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE departement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE courrier DROP CONSTRAINT FK_BEF47CAA10335F61');
        $this->addSql('ALTER TABLE courrier_user DROP CONSTRAINT FK_343667B8BF41DC7');
        $this->addSql('ALTER TABLE courrier_user DROP CONSTRAINT FK_343667BA76ED395');
        $this->addSql('DROP TABLE courrier');
        $this->addSql('DROP TABLE courrier_user');
        $this->addSql('DROP TABLE expediteur');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE "user" ADD courrier_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_8d93d6498bf41dc7 ON "user" (courrier_id)');
    }
}
