<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171229190814 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_type_id INT DEFAULT NULL, address LONGTEXT NOT NULL, date_time DATETIME NOT NULL, registration_end DATETIME NOT NULL, capacity INT NOT NULL, notification_threshold INT NOT NULL, template_override VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA7401B253C (event_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_languages (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, language_id INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_26446D4471F7E88B (event_id), INDEX IDX_26446D4482F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_organizers (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, organizer_id INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_8A75E2D371F7E88B (event_id), INDEX IDX_8A75E2D3876C4DDA (organizer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE event_languages ADD CONSTRAINT FK_26446D4471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_languages ADD CONSTRAINT FK_26446D4482F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE event_organizers ADD CONSTRAINT FK_8A75E2D371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_organizers ADD CONSTRAINT FK_8A75E2D3876C4DDA FOREIGN KEY (organizer_id) REFERENCES organizer (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_languages DROP FOREIGN KEY FK_26446D4471F7E88B');
        $this->addSql('ALTER TABLE event_organizers DROP FOREIGN KEY FK_8A75E2D371F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_languages');
        $this->addSql('DROP TABLE event_organizers');
    }
}
