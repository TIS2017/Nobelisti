<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171210105441 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE email_log_status (id INT AUTO_INCREMENT NOT NULL, content_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, response LONGTEXT DEFAULT NULL, INDEX IDX_C249C46184A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_log_content (id INT AUTO_INCREMENT NOT NULL, email_address VARCHAR(255) NOT NULL, email_type VARCHAR(100) NOT NULL, email_meta LONGTEXT DEFAULT NULL, content_plain LONGTEXT NOT NULL, content_html LONGTEXT DEFAULT NULL, template VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_log_status ADD CONSTRAINT FK_C249C46184A0A3ED FOREIGN KEY (content_id) REFERENCES email_log_content (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email_log_status DROP FOREIGN KEY FK_C249C46184A0A3ED');
        $this->addSql('DROP TABLE email_log_status');
        $this->addSql('DROP TABLE email_log_content');
    }
}
