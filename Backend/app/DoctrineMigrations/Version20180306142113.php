<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180306142113 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE device_notification_repeat_history (id INT AUTO_INCREMENT NOT NULL, notification_id INT DEFAULT NULL, parent INT NOT NULL, date_added DATETIME DEFAULT NULL, INDEX IDX_F7D7726CEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_notification_repeat_month (id INT AUTO_INCREMENT NOT NULL, notification_id INT DEFAULT NULL, month INT DEFAULT NULL, month_days LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_F70CCB8CEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_notification_repeat_month_day (id INT AUTO_INCREMENT NOT NULL, notification_id INT DEFAULT NULL, month_day INT DEFAULT NULL, INDEX IDX_1DD268FBEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_notification_repeat_week_day (id INT AUTO_INCREMENT NOT NULL, notification_id INT DEFAULT NULL, week_day INT DEFAULT NULL, INDEX IDX_80B7CB08EF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device_notification_repeat_history ADD CONSTRAINT FK_F7D7726CEF1A9D84 FOREIGN KEY (notification_id) REFERENCES device_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification_repeat_month ADD CONSTRAINT FK_F70CCB8CEF1A9D84 FOREIGN KEY (notification_id) REFERENCES device_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification_repeat_month_day ADD CONSTRAINT FK_1DD268FBEF1A9D84 FOREIGN KEY (notification_id) REFERENCES device_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification_repeat_week_day ADD CONSTRAINT FK_80B7CB08EF1A9D84 FOREIGN KEY (notification_id) REFERENCES device_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification ADD repeat_subunit INT DEFAULT NULL, ADD repeat_week INT DEFAULT NULL');
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
        $this->addSql('DROP TABLE device_notification_repeat_history');
        $this->addSql('DROP TABLE device_notification_repeat_month');
        $this->addSql('DROP TABLE device_notification_repeat_month_day');
        $this->addSql('DROP TABLE device_notification_repeat_week_day');
        $this->addSql('ALTER TABLE device_notification DROP repeat_subunit, DROP repeat_week');
    }
}
