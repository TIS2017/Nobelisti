<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180130141440 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, attendee_id INT DEFAULT NULL, event_details_id INT DEFAULT NULL, language_id INT DEFAULT NULL, code INT NOT NULL, confirmed DATETIME NOT NULL, confirmation_token VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_62A8A7A7BCFD782A (attendee_id), INDEX IDX_62A8A7A7E3F4DE0D (event_details_id), INDEX IDX_62A8A7A782F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7BCFD782A FOREIGN KEY (attendee_id) REFERENCES attendee (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7E3F4DE0D FOREIGN KEY (event_details_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('DROP INDEX UNIQ_1150D567E7927C74 ON attendee');
        $this->addSql('ALTER TABLE attendee ADD language_id INT DEFAULT NULL, CHANGE first_name first_name VARCHAR(100) NOT NULL, CHANGE last_name last_name VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_1150D56782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_1150D56782F1BAF4 ON attendee (language_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE registration');
        $this->addSql('ALTER TABLE attendee DROP FOREIGN KEY FK_1150D56782F1BAF4');
        $this->addSql('DROP INDEX IDX_1150D56782F1BAF4 ON attendee');
        $this->addSql('ALTER TABLE attendee DROP language_id, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE email email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1150D567E7927C74 ON attendee (email)');
    }
}
