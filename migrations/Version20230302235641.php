<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302235641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, annonces_id INT NOT NULL, parent_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, rgpd TINYINT(1) NOT NULL, INDEX IDX_D9BEC0C44C2885D7 (annonces_id), INDEX IDX_D9BEC0C4727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C44C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4727ACA70 FOREIGN KEY (parent_id) REFERENCES commentaires (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C44C2885D7');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4727ACA70');
        $this->addSql('DROP TABLE commentaires');
    }
}
