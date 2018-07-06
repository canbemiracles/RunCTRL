<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171222162116 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) NULL');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE assignment_answer ADD branch_shift_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assignment_answer ADD CONSTRAINT FK_E2FC6A385CD365CD FOREIGN KEY (branch_shift_id) REFERENCES branch_shift (id)');
        $this->addSql('CREATE INDEX IDX_E2FC6A385CD365CD ON assignment_answer (branch_shift_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignment_answer DROP FOREIGN KEY FK_E2FC6A385CD365CD');
        $this->addSql('DROP INDEX IDX_E2FC6A385CD365CD ON assignment_answer');
        $this->addSql('ALTER TABLE assignment_answer DROP branch_shift_id');
        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
