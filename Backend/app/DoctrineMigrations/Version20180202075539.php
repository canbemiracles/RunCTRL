<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180202075539 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignment_repeat_month (id INT AUTO_INCREMENT NOT NULL, assignment_id INT DEFAULT NULL, month INT DEFAULT NULL, INDEX IDX_8FCFE91BD19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignment_repeat_month_day (id INT AUTO_INCREMENT NOT NULL, assignment_id INT DEFAULT NULL, month_day INT DEFAULT NULL, INDEX IDX_B8855EEBD19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignment_repeat_week_day (id INT AUTO_INCREMENT NOT NULL, assignment_id INT DEFAULT NULL, week_day INT DEFAULT NULL, INDEX IDX_5433E21D19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment_repeat_month ADD CONSTRAINT FK_8FCFE91BD19302F8 FOREIGN KEY (assignment_id) REFERENCES `assignment` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_repeat_month_day ADD CONSTRAINT FK_B8855EEBD19302F8 FOREIGN KEY (assignment_id) REFERENCES `assignment` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_repeat_week_day ADD CONSTRAINT FK_5433E21D19302F8 FOREIGN KEY (assignment_id) REFERENCES `assignment` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment DROP repeat_week_day, DROP repeat_month');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE assignment_repeat_month');
        $this->addSql('DROP TABLE assignment_repeat_month_day');
        $this->addSql('DROP TABLE assignment_repeat_week_day');
        $this->addSql('ALTER TABLE `assignment` ADD repeat_week_day LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD repeat_month LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD repeat_month_day LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
