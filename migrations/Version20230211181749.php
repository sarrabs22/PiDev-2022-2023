<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230211181749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don ADD category_d_id INT DEFAULT NULL, ADD image LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9879BD92D FOREIGN KEY (category_d_id) REFERENCES category_d (id)');
        $this->addSql('CREATE INDEX IDX_F8F081D9879BD92D ON don (category_d_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9879BD92D');
        $this->addSql('DROP INDEX IDX_F8F081D9879BD92D ON don');
        $this->addSql('ALTER TABLE don DROP category_d_id, DROP image');
    }
}
