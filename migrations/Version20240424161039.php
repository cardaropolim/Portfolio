<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424161039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD gallerie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C8FE05BFA FOREIGN KEY (gallerie_id) REFERENCES gallerie (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C8FE05BFA ON media (gallerie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C8FE05BFA');
        $this->addSql('DROP INDEX IDX_6A2CA10C8FE05BFA ON media');
        $this->addSql('ALTER TABLE media DROP gallerie_id');
    }
}
