<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303213207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP star1, DROP star2, DROP star3, DROP star4, DROP star5');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces ADD star1 INT DEFAULT NULL, ADD star2 INT DEFAULT NULL, ADD star3 INT DEFAULT NULL, ADD star4 INT DEFAULT NULL, ADD star5 INT DEFAULT NULL');
    }
}
