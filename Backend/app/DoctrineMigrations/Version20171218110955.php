<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171218110955 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE emergency_phone');
        $this->addSql('ALTER TABLE branch ADD police_phone VARCHAR(255) DEFAULT NULL, ADD fire_phone VARCHAR(255) DEFAULT NULL, ADD ambulance_phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE emergency_phone (id INT AUTO_INCREMENT NOT NULL, branch_id INT DEFAULT NULL, phone_number VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, type VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_C4418F20DCD6CC49 (branch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emergency_phone ADD CONSTRAINT FK_C4418F20DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE branch DROP police_phone, DROP fire_phone, DROP ambulance_phone');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
