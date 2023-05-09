<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508203214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claim DROP INDEX UNIQ_A769DE27EDEA38B, ADD INDEX IDX_A769DE27EDEA38B (donner_id)');
        $this->addSql('ALTER TABLE claim DROP INDEX UNIQ_A769DE27CD53EDB6, ADD INDEX IDX_A769DE27CD53EDB6 (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claim DROP INDEX IDX_A769DE27EDEA38B, ADD UNIQUE INDEX UNIQ_A769DE27EDEA38B (donner_id)');
        $this->addSql('ALTER TABLE claim DROP INDEX IDX_A769DE27CD53EDB6, ADD UNIQUE INDEX UNIQ_A769DE27CD53EDB6 (receiver_id)');
    }
}
