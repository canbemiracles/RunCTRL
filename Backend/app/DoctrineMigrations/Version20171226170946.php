<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171226170946 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE country ADD date_format_id INT DEFAULT NULL, ADD is_state TINYINT(1) NOT NULL, ADD workday_start SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966E95FC5B1 FOREIGN KEY (date_format_id) REFERENCES date_format (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5373C966E95FC5B1 ON country (date_format_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C966E95FC5B1');
        $this->addSql('DROP INDEX IDX_5373C966E95FC5B1 ON country');
        $this->addSql('ALTER TABLE country DROP date_format_id, DROP is_state, DROP workday_start');
    }
}
