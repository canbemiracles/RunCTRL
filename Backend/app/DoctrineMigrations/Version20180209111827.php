<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180209111827 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE announcement_notifications_users (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6DE015F3EF1A9D84 (notification_id), INDEX IDX_6DE015F3A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_alert ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification_alert ADD CONSTRAINT FK_D149A338A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE announcement_notifications_users ADD CONSTRAINT FK_6DE015F3EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification_announcement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE announcement_notifications_users ADD CONSTRAINT FK_6DE015F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification_report ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification_report ADD CONSTRAINT FK_8889D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8889D9A76ED395 ON notification_report (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A183753D32');
        $this->addSql('ALTER TABLE announcement_notifications_users DROP FOREIGN KEY FK_6DE015F3EF1A9D84');
        $this->addSql('CREATE TABLE user_invitation (code VARCHAR(6) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(256) NOT NULL COLLATE utf8_unicode_ci, sent TINYINT(1) NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE family_statuses');
        $this->addSql('DROP TABLE notification_alert');
        $this->addSql('DROP TABLE notification_announcement');
        $this->addSql('DROP TABLE announcement_notifications_users');
        $this->addSql('ALTER TABLE `assignment` ADD repeat_week_day INT DEFAULT NULL, ADD repeat_month INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assignment_repeat_week_day RENAME INDEX idx_dd34b02fd19302f8 TO IDX_5433E21D19302F8');
        $this->addSql('ALTER TABLE companies_weekends RENAME INDEX idx_67a083f8979b1ad6 TO IDX_9729E833979B1AD6');
        $this->addSql('ALTER TABLE companies_weekends RENAME INDEX idx_67a083f8851ad333 TO IDX_9729E833851AD333');
        $this->addSql('DROP INDEX IDX_5D9F75A183753D32 ON employee');
        $this->addSql('ALTER TABLE employee DROP family_situation_id');
        $this->addSql('ALTER TABLE notification ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('ALTER TABLE notification_report DROP FOREIGN KEY FK_8889D9A76ED395');
        $this->addSql('DROP INDEX IDX_8889D9A76ED395 ON notification_report');
        $this->addSql('ALTER TABLE notification_report DROP user_id');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE u_t_c DROP FOREIGN KEY FK_DE1E36A3CBAB9ECD');
        $this->addSql('ALTER TABLE u_t_c ADD CONSTRAINT FK_DE1E36A3CBAB9ECD FOREIGN KEY (time_zone_id) REFERENCES time_zone (id) ON DELETE CASCADE');
    }
}
