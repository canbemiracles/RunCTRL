<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171205141618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE emergency_phone DROP FOREIGN KEY FK_C4418F20F92F3E70');
        $this->addSql('DROP INDEX IDX_C4418F20F92F3E70 ON emergency_phone');
        $this->addSql('ALTER TABLE emergency_phone CHANGE country_id branch_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emergency_phone ADD CONSTRAINT FK_C4418F20DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C4418F20DCD6CC49 ON emergency_phone (branch_id)');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE emergency_phone DROP FOREIGN KEY FK_C4418F20DCD6CC49');
        $this->addSql('DROP INDEX IDX_C4418F20DCD6CC49 ON emergency_phone');
        $this->addSql('ALTER TABLE emergency_phone CHANGE branch_id country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emergency_phone ADD CONSTRAINT FK_C4418F20F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C4418F20F92F3E70 ON emergency_phone (country_id)');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
