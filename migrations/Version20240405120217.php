<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405120217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photographe ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photographe ADD CONSTRAINT FK_50DF4A8BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_50DF4A8BA76ED395 ON photographe (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photographe DROP FOREIGN KEY FK_50DF4A8BA76ED395');
        $this->addSql('DROP INDEX UNIQ_50DF4A8BA76ED395 ON photographe');
        $this->addSql('ALTER TABLE photographe DROP user_id');
    }
}
