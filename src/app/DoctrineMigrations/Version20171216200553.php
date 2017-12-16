<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171216200553 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(50) NOT NULL, code VARCHAR(6) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D4DB71B5D4DB71B5 (language), UNIQUE INDEX UNIQ_D4DB71B577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_log_content ADD created DATETIME NOT NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE email_log_status ADD created DATETIME NOT NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE language');
        $this->addSql('ALTER TABLE email_log_content DROP created, DROP updated, DROP deleted');
        $this->addSql('ALTER TABLE email_log_status DROP created, DROP updated, DROP deleted');
    }
}
