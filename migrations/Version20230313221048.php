<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313221048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_d (id INT AUTO_INCREMENT NOT NULL, name_ca VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE don (id INT AUTO_INCREMENT NOT NULL, category_d_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name_d VARCHAR(100) NOT NULL, quantite INT NOT NULL, description LONGTEXT NOT NULL, localisation VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, numero INT NOT NULL, INDEX IDX_F8F081D9879BD92D (category_d_id), INDEX IDX_F8F081D9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9879BD92D FOREIGN KEY (category_d_id) REFERENCES category_d (id)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9879BD92D');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9A76ED395');
        $this->addSql('DROP TABLE category_d');
        $this->addSql('DROP TABLE don');
    }
}
