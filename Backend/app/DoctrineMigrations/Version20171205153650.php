<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171205153650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE geographical_area CHANGE region region VARCHAR(80) DEFAULT NULL, CHANGE city city VARCHAR(80) DEFAULT NULL, CHANGE street_address street_address VARCHAR(100) DEFAULT NULL, CHANGE zip zip VARCHAR(10) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geographical_area CHANGE region region VARCHAR(80) NOT NULL COLLATE utf8_unicode_ci, CHANGE city city VARCHAR(80) NOT NULL COLLATE utf8_unicode_ci, CHANGE street_address street_address VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, CHANGE zip zip VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
