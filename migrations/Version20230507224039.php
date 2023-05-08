<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507224039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27BE20CAB0');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE276F6021A6');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27A4EEC4AA');
        $this->addSql('DROP INDEX UNIQ_A769DE27A4EEC4AA ON claim');
        $this->addSql('DROP INDEX UNIQ_A769DE276F6021A6 ON claim');
        $this->addSql('DROP INDEX UNIQ_A769DE27BE20CAB0 ON claim');
        $this->addSql('ALTER TABLE claim ADD donation_id INT DEFAULT NULL, ADD donner_id INT DEFAULT NULL, ADD receiver_id INT DEFAULT NULL, DROP donation_id_id, DROP donner_id_id, DROP receiver_id_id');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE274DC1279C FOREIGN KEY (donation_id) REFERENCES don (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27EDEA38B FOREIGN KEY (donner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE274DC1279C ON claim (donation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE27EDEA38B ON claim (donner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE27CD53EDB6 ON claim (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE274DC1279C');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27EDEA38B');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27CD53EDB6');
        $this->addSql('DROP INDEX UNIQ_A769DE274DC1279C ON claim');
        $this->addSql('DROP INDEX UNIQ_A769DE27EDEA38B ON claim');
        $this->addSql('DROP INDEX UNIQ_A769DE27CD53EDB6 ON claim');
        $this->addSql('ALTER TABLE claim ADD donation_id_id INT DEFAULT NULL, ADD donner_id_id INT DEFAULT NULL, ADD receiver_id_id INT DEFAULT NULL, DROP donation_id, DROP donner_id, DROP receiver_id');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27BE20CAB0 FOREIGN KEY (receiver_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE276F6021A6 FOREIGN KEY (donner_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27A4EEC4AA FOREIGN KEY (donation_id_id) REFERENCES don (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE27A4EEC4AA ON claim (donation_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE276F6021A6 ON claim (donner_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A769DE27BE20CAB0 ON claim (receiver_id_id)');
    }
}
