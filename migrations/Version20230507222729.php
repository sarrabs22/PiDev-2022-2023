<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507222729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement_user DROP FOREIGN KEY FK_2EC0B3C4A76ED395');
        $this->addSql('ALTER TABLE evenement_user DROP FOREIGN KEY FK_2EC0B3C4FD02F13');
        $this->addSql('DROP TABLE evenement_user');
        $this->addSql('ALTER TABLE annonces CHANGE etoile_1 etoile_1 INT DEFAULT NULL, CHANGE etoile_2 etoile_2 INT DEFAULT NULL, CHANGE etoile_3 etoile_3 INT DEFAULT NULL, CHANGE etoile_4 etoile_4 INT DEFAULT NULL, CHANGE etoile_5 etoile_5 INT DEFAULT NULL');
        $this->addSql('ALTER TABLE claim ADD donation_id_id INT DEFAULT NULL, ADD donner_id_id INT DEFAULT NULL, ADD receiver_id_id INT DEFAULT NULL, DROP donation_id, DROP donner_id, DROP receiver_id, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE total_quantity total_quantity INT NOT NULL, CHANGE received_quantity received_quantity INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27A4EEC4AA FOREIGN KEY (donation_id_id) REFERENCES don (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE276F6021A6 FOREIGN KEY (donner_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27BE20CAB0 FOREIGN KEY (receiver_id_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE27A4EEC4AA ON claim (donation_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE276F6021A6 ON claim (donner_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE27BE20CAB0 ON claim (receiver_id_id)');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY fk_user');
        $this->addSql('DROP INDEX fk_user ON membre');
        $this->addSql('ALTER TABLE membre DROP user_id');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY fk_user_annonce_user_id');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY fk_user_annonce_annonce_id');
        $this->addSql('DROP INDEX fk_user_annonce_user_id ON rating');
        $this->addSql('DROP INDEX fk_user_annonce_annonce_id ON rating');
        $this->addSql('ALTER TABLE rating ADD user_id_id INT DEFAULT NULL, ADD annonce_id_id INT DEFAULT NULL, DROP user_id, DROP annonce_id');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926229D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262268C955C8 FOREIGN KEY (annonce_id_id) REFERENCES annonces (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D88926229D86650F ON rating (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D889262268C955C8 ON rating (annonce_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement_user (evenement_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2EC0B3C4FD02F13 (evenement_id), INDEX IDX_2EC0B3C4A76ED395 (user_id), PRIMARY KEY(evenement_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evenement_user ADD CONSTRAINT FK_2EC0B3C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_user ADD CONSTRAINT FK_2EC0B3C4FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonces CHANGE etoile_1 etoile_1 INT DEFAULT 0 NOT NULL, CHANGE etoile_2 etoile_2 INT DEFAULT 0 NOT NULL, CHANGE etoile_3 etoile_3 INT DEFAULT 0 NOT NULL, CHANGE etoile_4 etoile_4 INT DEFAULT 0 NOT NULL, CHANGE etoile_5 etoile_5 INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE claim MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27A4EEC4AA');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE276F6021A6');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27BE20CAB0');
        $this->addSql('DROP INDEX UNIQ_A769DE27A4EEC4AA ON claim');
        $this->addSql('DROP INDEX UNIQ_A769DE276F6021A6 ON claim');
        $this->addSql('DROP INDEX UNIQ_A769DE27BE20CAB0 ON claim');
        $this->addSql('DROP INDEX `primary` ON claim');
        $this->addSql('ALTER TABLE claim ADD donation_id INT DEFAULT NULL, ADD donner_id INT DEFAULT NULL, ADD receiver_id INT DEFAULT NULL, DROP donation_id_id, DROP donner_id_id, DROP receiver_id_id, CHANGE id id INT NOT NULL, CHANGE total_quantity total_quantity INT DEFAULT NULL, CHANGE received_quantity received_quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membre ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX fk_user ON membre (user_id)');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926229D86650F');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262268C955C8');
        $this->addSql('DROP INDEX UNIQ_D88926229D86650F ON rating');
        $this->addSql('DROP INDEX UNIQ_D889262268C955C8 ON rating');
        $this->addSql('ALTER TABLE rating ADD user_id INT NOT NULL, ADD annonce_id INT NOT NULL, DROP user_id_id, DROP annonce_id_id');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT fk_user_annonce_user_id FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT fk_user_annonce_annonce_id FOREIGN KEY (annonce_id) REFERENCES annonces (id)');
        $this->addSql('CREATE INDEX fk_user_annonce_user_id ON rating (user_id)');
        $this->addSql('CREATE INDEX fk_user_annonce_annonce_id ON rating (annonce_id)');
    }
}
