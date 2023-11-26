<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231126174917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, serie_im_db_id VARCHAR(255) NOT NULL, episode_im_db_id VARCHAR(255) NOT NULL, season INT NOT NULL, episode_im_db_title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE im_dbentry (id INT AUTO_INCREMENT NOT NULL, category_id_id INT NOT NULL, im_db_id VARCHAR(255) NOT NULL, im_db_title VARCHAR(255) NOT NULL, im_db_image_url VARCHAR(255) NOT NULL, is_serie TINYINT(1) NOT NULL, INDEX IDX_38946C9F9777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE im_dbentry ADD CONSTRAINT FK_38946C9F9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE im_dbentry DROP FOREIGN KEY FK_38946C9F9777D11E');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE im_dbentry');
    }
}
