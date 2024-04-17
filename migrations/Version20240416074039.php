<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416074039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE destination_media DROP FOREIGN KEY FK_3B5100BF816C6140');
        $this->addSql('ALTER TABLE destination_media DROP FOREIGN KEY FK_3B5100BFEA9FDD75');
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE destination_media');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, section1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, section2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, section3 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, section4 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE destination_media (destination_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_3B5100BFEA9FDD75 (media_id), INDEX IDX_3B5100BF816C6140 (destination_id), PRIMARY KEY(destination_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE destination_media ADD CONSTRAINT FK_3B5100BF816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE destination_media ADD CONSTRAINT FK_3B5100BFEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
    }
}
