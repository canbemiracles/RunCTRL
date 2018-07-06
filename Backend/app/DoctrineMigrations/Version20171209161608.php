<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171209161608 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE custom_notification (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE custom_notifications_users (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_96F355C8EF1A9D84 (notification_id), INDEX IDX_96F355C8A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE custom_notifications_users ADD CONSTRAINT FK_96F355C8EF1A9D84 FOREIGN KEY (notification_id) REFERENCES custom_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE custom_notifications_users ADD CONSTRAINT FK_96F355C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_notifications_users DROP FOREIGN KEY FK_96F355C8EF1A9D84');
        $this->addSql('DROP TABLE custom_notification');
        $this->addSql('DROP TABLE custom_notifications_users');
    }
}
