<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117160128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE website_settings (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE home_pages ADD website_settings_id INT NOT NULL');
        $this->addSql('ALTER TABLE home_pages ADD CONSTRAINT FK_2B8E1CE512116101 FOREIGN KEY (website_settings_id) REFERENCES website_settings (id)');
        $this->addSql('CREATE INDEX IDX_2B8E1CE512116101 ON home_pages (website_settings_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE home_pages DROP FOREIGN KEY FK_2B8E1CE512116101');
        $this->addSql('DROP TABLE website_settings');
        $this->addSql('DROP INDEX IDX_2B8E1CE512116101 ON home_pages');
        $this->addSql('ALTER TABLE home_pages DROP website_settings_id');
    }
}
