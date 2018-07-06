<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171224113922 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignment_answer DROP FOREIGN KEY FK_E2FC6A385CD365CD');
        $this->addSql('ALTER TABLE assignment_answer DROP FOREIGN KEY FK_E2FC6A388C03F15C');
        $this->addSql('ALTER TABLE assignment_answer DROP FOREIGN KEY FK_E2FC6A38D19302F8');
        $this->addSql('ALTER TABLE assignment_answer ADD CONSTRAINT FK_E2FC6A385CD365CD FOREIGN KEY (branch_shift_id) REFERENCES branch_shift (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_answer ADD CONSTRAINT FK_E2FC6A388C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_answer ADD CONSTRAINT FK_E2FC6A38D19302F8 FOREIGN KEY (assignment_id) REFERENCES `assignment` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks DROP FOREIGN KEY FK_6F82122E8DB60186');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks DROP FOREIGN KEY FK_6F82122EB16D08A7');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks ADD CONSTRAINT FK_6F82122E8DB60186 FOREIGN KEY (task_id) REFERENCES assignment_checklist_tasks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks ADD CONSTRAINT FK_6F82122EB16D08A7 FOREIGN KEY (checklist_id) REFERENCES assignment_answer_check_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BA21BDB235');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BAD60322AC');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA21BDB235 FOREIGN KEY (station_id) REFERENCES branch_station (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BAD60322AC FOREIGN KEY (role_id) REFERENCES branch_station_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_history_problem_task DROP FOREIGN KEY FK_CF8A0590D19302F8');
        $this->addSql('ALTER TABLE assignment_history_problem_task ADD CONSTRAINT FK_CF8A0590D19302F8 FOREIGN KEY (assignment_id) REFERENCES `assignment` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification_branch DROP FOREIGN KEY FK_89D0C121DCD6CC49');
        $this->addSql('ALTER TABLE device_notification_branch ADD CONSTRAINT FK_89D0C121DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification_message DROP FOREIGN KEY FK_77EE7B0E8C03F15C');
        $this->addSql('ALTER TABLE device_notification_message ADD CONSTRAINT FK_77EE7B0E8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification_role DROP FOREIGN KEY FK_5F5B5FF7D60322AC');
        $this->addSql('ALTER TABLE device_notification_role ADD CONSTRAINT FK_5F5B5FF7D60322AC FOREIGN KEY (role_id) REFERENCES branch_station_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_notification_station DROP FOREIGN KEY FK_5E6AB3C021BDB235');
        $this->addSql('ALTER TABLE device_notification_station ADD CONSTRAINT FK_5E6AB3C021BDB235 FOREIGN KEY (station_id) REFERENCES branch_station (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `assignment` DROP FOREIGN KEY FK_30C544BAD60322AC');
        $this->addSql('ALTER TABLE `assignment` DROP FOREIGN KEY FK_30C544BA21BDB235');
        $this->addSql('ALTER TABLE `assignment` ADD CONSTRAINT FK_30C544BAD60322AC FOREIGN KEY (role_id) REFERENCES branch_station_role (id)');
        $this->addSql('ALTER TABLE `assignment` ADD CONSTRAINT FK_30C544BA21BDB235 FOREIGN KEY (station_id) REFERENCES branch_station (id)');
        $this->addSql('ALTER TABLE assignment_answer DROP FOREIGN KEY FK_E2FC6A38D19302F8');
        $this->addSql('ALTER TABLE assignment_answer DROP FOREIGN KEY FK_E2FC6A388C03F15C');
        $this->addSql('ALTER TABLE assignment_answer DROP FOREIGN KEY FK_E2FC6A385CD365CD');
        $this->addSql('ALTER TABLE assignment_answer ADD CONSTRAINT FK_E2FC6A38D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id)');
        $this->addSql('ALTER TABLE assignment_answer ADD CONSTRAINT FK_E2FC6A388C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE assignment_answer ADD CONSTRAINT FK_E2FC6A385CD365CD FOREIGN KEY (branch_shift_id) REFERENCES branch_shift (id)');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks DROP FOREIGN KEY FK_6F82122EB16D08A7');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks DROP FOREIGN KEY FK_6F82122E8DB60186');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks ADD CONSTRAINT FK_6F82122EB16D08A7 FOREIGN KEY (checklist_id) REFERENCES assignment_answer_check_list (id)');
        $this->addSql('ALTER TABLE assignment_answer_check_list_done_tasks ADD CONSTRAINT FK_6F82122E8DB60186 FOREIGN KEY (task_id) REFERENCES assignment_checklist_tasks (id)');
        $this->addSql('ALTER TABLE assignment_history_problem_task DROP FOREIGN KEY FK_CF8A0590D19302F8');
        $this->addSql('ALTER TABLE assignment_history_problem_task ADD CONSTRAINT FK_CF8A0590D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id)');
        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE device_notification_branch DROP FOREIGN KEY FK_89D0C121DCD6CC49');
        $this->addSql('ALTER TABLE device_notification_branch ADD CONSTRAINT FK_89D0C121DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('ALTER TABLE device_notification_message DROP FOREIGN KEY FK_77EE7B0E8C03F15C');
        $this->addSql('ALTER TABLE device_notification_message ADD CONSTRAINT FK_77EE7B0E8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE device_notification_role DROP FOREIGN KEY FK_5F5B5FF7D60322AC');
        $this->addSql('ALTER TABLE device_notification_role ADD CONSTRAINT FK_5F5B5FF7D60322AC FOREIGN KEY (role_id) REFERENCES branch_station_role (id)');
        $this->addSql('ALTER TABLE device_notification_station DROP FOREIGN KEY FK_5E6AB3C021BDB235');
        $this->addSql('ALTER TABLE device_notification_station ADD CONSTRAINT FK_5E6AB3C021BDB235 FOREIGN KEY (station_id) REFERENCES branch_station (id)');
    }
}
