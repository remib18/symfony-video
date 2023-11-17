<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117163525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE website_settings (id INT AUTO_INCREMENT NOT NULL, active_homepage_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3452E948518E1891 (active_homepage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE website_settings ADD CONSTRAINT FK_3452E948518E1891 FOREIGN KEY (active_homepage_id) REFERENCES home_pages (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_settings DROP FOREIGN KEY FK_3452E948518E1891');
        $this->addSql('DROP TABLE website_settings');
    }
}
