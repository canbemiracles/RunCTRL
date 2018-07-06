<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208113558 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE branch_shift CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE permissions_groups DROP FOREIGN KEY FK_909648BDFE54D947');
        $this->addSql('ALTER TABLE permissions_groups DROP FOREIGN KEY FK_909648BDFED90CCA');
        $this->addSql('ALTER TABLE permissions_groups DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE permissions_groups ADD CONSTRAINT FK_909648BDFE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permissions_groups ADD CONSTRAINT FK_909648BDFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE permissions_groups ADD PRIMARY KEY (group_id, permission_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE branch_shift CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE permissions_groups DROP FOREIGN KEY FK_909648BDFE54D947');
        $this->addSql('ALTER TABLE permissions_groups DROP FOREIGN KEY FK_909648BDFED90CCA');
        $this->addSql('ALTER TABLE permissions_groups DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE permissions_groups ADD CONSTRAINT FK_909648BDFE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE permissions_groups ADD CONSTRAINT FK_909648BDFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permissions_groups ADD PRIMARY KEY (permission_id, group_id)');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
