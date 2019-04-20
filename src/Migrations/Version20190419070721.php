<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190419070721 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ldap_user (id INT AUTO_INCREMENT NOT NULL, domain_id_id INT NOT NULL, login VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, biuro VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, department VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_3888D380AC3FB681 (domain_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_numbers (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_E7DC46CB9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ldap_user ADD CONSTRAINT FK_3888D380AC3FB681 FOREIGN KEY (domain_id_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE phone_numbers ADD CONSTRAINT FK_E7DC46CB9D86650F FOREIGN KEY (user_id_id) REFERENCES ldap_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE phone_numbers DROP FOREIGN KEY FK_E7DC46CB9D86650F');
        $this->addSql('DROP TABLE ldap_user');
        $this->addSql('DROP TABLE phone_numbers');
    }
}
