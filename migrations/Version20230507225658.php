<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507225658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE association_membre (association_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_C5C0CE5EEFB9C8A5 (association_id), INDEX IDX_C5C0CE5E6A99F74A (membre_id), PRIMARY KEY(association_id, membre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association_membre ADD CONSTRAINT FK_C5C0CE5EEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE association_membre ADD CONSTRAINT FK_C5C0CE5E6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association_membre DROP FOREIGN KEY FK_C5C0CE5EEFB9C8A5');
        $this->addSql('ALTER TABLE association_membre DROP FOREIGN KEY FK_C5C0CE5E6A99F74A');
        $this->addSql('DROP TABLE association_membre');
    }
}
