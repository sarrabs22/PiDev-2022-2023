<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313204118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCBCF5E72D');
        $this->addSql('DROP INDEX IDX_FD8521CCBCF5E72D ON association');
        $this->addSql('ALTER TABLE association CHANGE categorie_id categorieassociation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CC432669C1 FOREIGN KEY (categorieassociation_id) REFERENCES categorie_association (id)');
        $this->addSql('CREATE INDEX IDX_FD8521CC432669C1 ON association (categorieassociation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CC432669C1');
        $this->addSql('DROP INDEX IDX_FD8521CC432669C1 ON association');
        $this->addSql('ALTER TABLE association CHANGE categorieassociation_id categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_association (id)');
        $this->addSql('CREATE INDEX IDX_FD8521CCBCF5E72D ON association (categorie_id)');
    }
}
