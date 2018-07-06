<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171206111649 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE branch ADD supervisor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE branch ADD CONSTRAINT FK_BB861B1F19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES user_supervisor (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BB861B1F19E9AC5F ON branch (supervisor_id)');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE user_supervisor DROP FOREIGN KEY FK_EAF65414DCD6CC49');
        $this->addSql('DROP INDEX IDX_EAF65414DCD6CC49 ON user_supervisor');
        $this->addSql('ALTER TABLE user_supervisor DROP branch_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE branch DROP FOREIGN KEY FK_BB861B1F19E9AC5F');
        $this->addSql('DROP INDEX IDX_BB861B1F19E9AC5F ON branch');
        $this->addSql('ALTER TABLE branch DROP supervisor_id');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE user_supervisor ADD branch_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_supervisor ADD CONSTRAINT FK_EAF65414DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_EAF65414DCD6CC49 ON user_supervisor (branch_id)');
    }
}
