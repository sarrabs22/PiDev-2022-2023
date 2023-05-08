<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507224322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262268C955C8');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926229D86650F');
        $this->addSql('DROP INDEX UNIQ_D889262268C955C8 ON rating');
        $this->addSql('DROP INDEX UNIQ_D88926229D86650F ON rating');
        $this->addSql('ALTER TABLE rating ADD user_id INT DEFAULT NULL, ADD annonce_id INT DEFAULT NULL, DROP user_id_id, DROP annonce_id_id');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926228805AB2F FOREIGN KEY (annonce_id) REFERENCES annonces (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8892622A76ED395 ON rating (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D88926228805AB2F ON rating (annonce_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926228805AB2F');
        $this->addSql('DROP INDEX UNIQ_D8892622A76ED395 ON rating');
        $this->addSql('DROP INDEX UNIQ_D88926228805AB2F ON rating');
        $this->addSql('ALTER TABLE rating ADD user_id_id INT DEFAULT NULL, ADD annonce_id_id INT DEFAULT NULL, DROP user_id, DROP annonce_id');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262268C955C8 FOREIGN KEY (annonce_id_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926229D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D889262268C955C8 ON rating (annonce_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D88926229D86650F ON rating (user_id_id)');
    }
}
