<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180113211405 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE email_log_status');
        $this->addSql('ALTER TABLE email_log_content ADD status LONGTEXT NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE email_log_status (id INT AUTO_INCREMENT NOT NULL, content_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, response LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_C249C46184A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_log_status ADD CONSTRAINT FK_C249C46184A0A3ED FOREIGN KEY (content_id) REFERENCES email_log_content (id)');
        $this->addSql('ALTER TABLE email_log_content DROP status');
    }
}
