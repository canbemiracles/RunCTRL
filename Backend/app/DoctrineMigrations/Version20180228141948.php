<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180228141948 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE phone_number (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, phone_number VARCHAR(20) NOT NULL, prefix_number VARCHAR(10) DEFAULT NULL, INDEX IDX_6B01BC5BF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phone_number ADD CONSTRAINT FK_6B01BC5BF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee ADD phone_number_id INT DEFAULT NULL, DROP phone_number, DROP prefix_number');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A139DFD528 FOREIGN KEY (phone_number_id) REFERENCES phone_number (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A139DFD528 ON employee (phone_number_id)');
        $this->addSql('ALTER TABLE user ADD phone_number_id INT DEFAULT NULL, DROP phone_number');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64939DFD528 FOREIGN KEY (phone_number_id) REFERENCES phone_number (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64939DFD528 ON user (phone_number_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A139DFD528');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64939DFD528');
        $this->addSql('DROP TABLE phone_number');
        $this->addSql('DROP INDEX UNIQ_5D9F75A139DFD528 ON employee');
        $this->addSql('ALTER TABLE employee ADD phone_number VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, ADD prefix_number VARCHAR(5) NOT NULL COLLATE utf8_unicode_ci, DROP phone_number_id');
        $this->addSql('DROP INDEX UNIQ_8D93D64939DFD528 ON user');
        $this->addSql('ALTER TABLE user ADD phone_number VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, DROP phone_number_id');
    }
}
