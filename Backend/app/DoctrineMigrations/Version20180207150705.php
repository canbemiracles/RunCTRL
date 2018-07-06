<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180207150705 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee DROP INDEX UNIQ_5D9F75A183753D32, ADD INDEX IDX_5D9F75A183753D32 (family_situation_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `assignment` ADD repeat_week_day LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD repeat_month LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD repeat_month_day LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE companies_weekends RENAME INDEX idx_67a083f8979b1ad6 TO IDX_9729E833979B1AD6');
        $this->addSql('ALTER TABLE companies_weekends RENAME INDEX idx_67a083f8851ad333 TO IDX_9729E833851AD333');
        $this->addSql('ALTER TABLE employee DROP INDEX IDX_5D9F75A183753D32, ADD UNIQUE INDEX UNIQ_5D9F75A183753D32 (family_situation_id)');
        $this->addSql('ALTER TABLE notification ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
