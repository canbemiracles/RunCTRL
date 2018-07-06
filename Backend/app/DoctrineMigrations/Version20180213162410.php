<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180213162410 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignment_repeat_assignment_repeat_history (id INT AUTO_INCREMENT NOT NULL, assignment_id INT DEFAULT NULL, parent INT NOT NULL, date_added DATETIME DEFAULT NULL, depth INT NOT NULL, INDEX IDX_C0715943D19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment_repeat_assignment_repeat_history ADD CONSTRAINT FK_C0715943D19302F8 FOREIGN KEY (assignment_id) REFERENCES `assignment` (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notifications_users (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5201E4C0EF1A9D84 (notification_id), INDEX IDX_5201E4C0A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notifications_users ADD CONSTRAINT FK_5201E4C0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notifications_users ADD CONSTRAINT FK_5201E4C0EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE assignment_repeat_assignment_repeat_history');
        $this->addSql('ALTER TABLE `assignment` ADD repeat_week_day LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD repeat_month LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD repeat_month_day LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE companies_weekends RENAME INDEX idx_67a083f8979b1ad6 TO IDX_9729E833979B1AD6');
        $this->addSql('ALTER TABLE companies_weekends RENAME INDEX idx_67a083f8851ad333 TO IDX_9729E833851AD333');
        $this->addSql('ALTER TABLE notification ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('ALTER TABLE notification_alert RENAME INDEX idx_d149a338a76ed395 TO FK_D149A338A76ED395');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
