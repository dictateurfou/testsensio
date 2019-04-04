<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210234430 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image ADD trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FB281BE2E ON image (trick_id)');
        $this->addSql('ALTER TABLE trick DROP image_list');
        $this->addSql('ALTER TABLE user CHANGE pseudo name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB281BE2E');
        $this->addSql('DROP INDEX IDX_C53D045FB281BE2E ON image');
        $this->addSql('ALTER TABLE image DROP trick_id');
        $this->addSql('ALTER TABLE trick ADD image_list JSON NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE name pseudo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
