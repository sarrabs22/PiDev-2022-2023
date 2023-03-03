<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302210446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, mail VARCHAR(255) NOT NULL, age INT NOT NULL, passions VARCHAR(500) NOT NULL, yes_experience VARCHAR(50) NOT NULL, experience VARCHAR(10000) NOT NULL, club_entendu VARCHAR(1000) NOT NULL, actions VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nom (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCBCF5E72D');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE nom');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCBCF5E72D');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
