<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027044146 extends AbstractMigration
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
        $this->addSql('CREATE TABLE courrier_user (courrier_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(courrier_id, user_id))');
        $this->addSql('CREATE INDEX IDX_343667B8BF41DC7 ON courrier_user (courrier_id)');
        $this->addSql('CREATE INDEX IDX_343667BA76ED395 ON courrier_user (user_id)');
        $this->addSql('CREATE TABLE expediteur (id INT NOT NULL, adresse VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE courrier_user ADD CONSTRAINT FK_343667B8BF41DC7 FOREIGN KEY (courrier_id) REFERENCES courrier (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE courrier_user ADD CONSTRAINT FK_343667BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE departement DROP CONSTRAINT fk_c1765b638bf41dc7');
        $this->addSql('DROP TABLE departement');
        $this->addSql('ALTER TABLE courrier ADD exped_courrier VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE courrier ADD status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE courrier ADD CONSTRAINT FK_BEF47CAA10335F61 FOREIGN KEY (expediteur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BEF47CAA10335F61 ON courrier (expediteur_id)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d6498bf41dc7');
        $this->addSql('DROP INDEX idx_8d93d6498bf41dc7');
        $this->addSql('ALTER TABLE "user" DROP courrier_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE expediteur_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE departement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE departement (id INT NOT NULL, courrier_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c1765b638bf41dc7 ON departement (courrier_id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT fk_c1765b638bf41dc7 FOREIGN KEY (courrier_id) REFERENCES courrier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE courrier_user DROP CONSTRAINT FK_343667B8BF41DC7');
        $this->addSql('ALTER TABLE courrier_user DROP CONSTRAINT FK_343667BA76ED395');
        $this->addSql('DROP TABLE courrier_user');
        $this->addSql('DROP TABLE expediteur');
        $this->addSql('ALTER TABLE courrier DROP CONSTRAINT FK_BEF47CAA10335F61');
        $this->addSql('DROP INDEX IDX_BEF47CAA10335F61');
        $this->addSql('ALTER TABLE courrier DROP exped_courrier');
        $this->addSql('ALTER TABLE courrier DROP status');
        $this->addSql('ALTER TABLE "user" ADD courrier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d6498bf41dc7 FOREIGN KEY (courrier_id) REFERENCES courrier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d6498bf41dc7 ON "user" (courrier_id)');
    }
}
