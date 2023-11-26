<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231126183454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im_dbentry_category (im_dbentry_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_494AB355AE244933 (im_dbentry_id), INDEX IDX_494AB35512469DE2 (category_id), PRIMARY KEY(im_dbentry_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE im_dbentry_category ADD CONSTRAINT FK_494AB355AE244933 FOREIGN KEY (im_dbentry_id) REFERENCES im_dbentry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE im_dbentry_category ADD CONSTRAINT FK_494AB35512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE im_dbentry DROP FOREIGN KEY FK_38946C9F9777D11E');
        $this->addSql('DROP INDEX IDX_38946C9F9777D11E ON im_dbentry');
        $this->addSql('ALTER TABLE im_dbentry DROP category_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE im_dbentry_category DROP FOREIGN KEY FK_494AB355AE244933');
        $this->addSql('ALTER TABLE im_dbentry_category DROP FOREIGN KEY FK_494AB35512469DE2');
        $this->addSql('DROP TABLE im_dbentry_category');
        $this->addSql('ALTER TABLE im_dbentry ADD category_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE im_dbentry ADD CONSTRAINT FK_38946C9F9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_38946C9F9777D11E ON im_dbentry (category_id_id)');
    }
}
