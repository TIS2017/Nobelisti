<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180213113238 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE registration CHANGE attendee_id attendee_id INT NOT NULL, CHANGE event_details_id event_details_id INT NOT NULL, CHANGE language_id language_id INT NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE event_type_id event_type_id INT NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event CHANGE event_type_id event_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registration CHANGE attendee_id attendee_id INT DEFAULT NULL, CHANGE event_details_id event_details_id INT DEFAULT NULL, CHANGE language_id language_id INT DEFAULT NULL');
    }
}
