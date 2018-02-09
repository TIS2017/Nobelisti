<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180209124131 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_type_languages ADD created DATETIME NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted DATETIME DEFAULT NULL, CHANGE event_type_id event_type_id INT NOT NULL, CHANGE language_id language_id INT NOT NULL');
        $this->addSql('UPDATE event_type_languages SET created=CURRENT_TIMESTAMP WHERE created IS NULL');
        $this->addSql('ALTER TABLE event_type_languages CHANGE created created DATETIME NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_type_languages DROP created, DROP updated, DROP deleted, CHANGE event_type_id event_type_id INT DEFAULT NULL, CHANGE language_id language_id INT DEFAULT NULL');
    }
}
