<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121161845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE home_pages (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, markdown VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, is_banned TINYINT(1) NOT NULL, image_link VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website_settings (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website_settings_home_pages (website_settings_id INT NOT NULL, home_pages_id INT NOT NULL, INDEX IDX_B29A06FB12116101 (website_settings_id), INDEX IDX_B29A06FB251F242A (home_pages_id), PRIMARY KEY(website_settings_id, home_pages_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE website_settings_home_pages ADD CONSTRAINT FK_B29A06FB12116101 FOREIGN KEY (website_settings_id) REFERENCES website_settings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE website_settings_home_pages ADD CONSTRAINT FK_B29A06FB251F242A FOREIGN KEY (home_pages_id) REFERENCES home_pages (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_settings_home_pages DROP FOREIGN KEY FK_B29A06FB12116101');
        $this->addSql('ALTER TABLE website_settings_home_pages DROP FOREIGN KEY FK_B29A06FB251F242A');
        $this->addSql('DROP TABLE home_pages');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE website_settings');
        $this->addSql('DROP TABLE website_settings_home_pages');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
