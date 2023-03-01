<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225172325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBCF5E72D');
        $this->addSql('ALTER TABLE evenement DROP materiel, CHANGE date_debut date_debut DATE NOT NULL, CHANGE image_event image_event VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE evenement_user DROP date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBCF5E72D');
        $this->addSql('ALTER TABLE evenement ADD materiel VARCHAR(255) DEFAULT NULL, CHANGE date_debut date_debut DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, CHANGE image_event image_event VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_user ADD date DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL');
    }
}
