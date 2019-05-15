<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190515113826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ldap_user CHANGE domain_id domain_id INT DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE biuro biuro VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE company company VARCHAR(255) DEFAULT NULL, CHANGE department department VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE phone_numbers DROP FOREIGN KEY FK_E7DC46CBA76ED395');
        $this->addSql('DROP INDEX IDX_E7DC46CBA76ED395 ON phone_numbers');
        $this->addSql('ALTER TABLE phone_numbers CHANGE user_id ldap_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE phone_numbers ADD CONSTRAINT FK_E7DC46CBCE024346 FOREIGN KEY (ldap_user_id) REFERENCES ldap_user (id)');
        $this->addSql('CREATE INDEX IDX_E7DC46CBCE024346 ON phone_numbers (ldap_user_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ldap_user CHANGE domain_id domain_id INT DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE biuro biuro VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE email email VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE company company VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE department department VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE description description VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE phone_numbers DROP FOREIGN KEY FK_E7DC46CBCE024346');
        $this->addSql('DROP INDEX IDX_E7DC46CBCE024346 ON phone_numbers');
        $this->addSql('ALTER TABLE phone_numbers CHANGE ldap_user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE phone_numbers ADD CONSTRAINT FK_E7DC46CBA76ED395 FOREIGN KEY (user_id) REFERENCES ldap_user (id)');
        $this->addSql('CREATE INDEX IDX_E7DC46CBA76ED395 ON phone_numbers (user_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
