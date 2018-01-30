<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122200144 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attendee (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, unsubscribed TINYINT(1) NOT NULL, email VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_1150D56782F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, attendee_id INT DEFAULT NULL, event_details_id INT DEFAULT NULL, language_id INT DEFAULT NULL, code INT NOT NULL, confirmed DATETIME NOT NULL, confirmation_token VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_62A8A7A7BCFD782A (attendee_id), INDEX IDX_62A8A7A7E3F4DE0D (event_details_id), INDEX IDX_62A8A7A782F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_1150D56782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7BCFD782A FOREIGN KEY (attendee_id) REFERENCES attendee (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7E3F4DE0D FOREIGN KEY (event_details_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7BCFD782A');
        $this->addSql('DROP TABLE attendee');
        $this->addSql('DROP TABLE registration');
    }
}
