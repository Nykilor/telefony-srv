<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190422200601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE domain CHANGE prefix prefix VARCHAR(255) DEFAULT NULL, CHANGE port port INT NOT NULL, CHANGE use_ssl use_ssl TINYINT(1) NOT NULL, CHANGE use_tls use_tls TINYINT(1) NOT NULL, CHANGE version version INT NOT NULL, CHANGE timeout timeout INT NOT NULL, CHANGE custom custom LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE domain CHANGE prefix prefix VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE port port INT DEFAULT 389 NOT NULL, CHANGE use_ssl use_ssl TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE use_tls use_tls TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE version version INT DEFAULT 3 NOT NULL, CHANGE timeout timeout INT DEFAULT 5 NOT NULL, CHANGE custom custom LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\'');
    }
}
