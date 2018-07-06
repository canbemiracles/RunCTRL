<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171215165947 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignment_history_problem_task (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, branch_shift_id INT DEFAULT NULL, assignment_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_CF8A05908C03F15C (employee_id), INDEX IDX_CF8A05905CD365CD (branch_shift_id), INDEX IDX_CF8A0590D19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment_history_problem_task ADD CONSTRAINT FK_CF8A05908C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_history_problem_task ADD CONSTRAINT FK_CF8A05905CD365CD FOREIGN KEY (branch_shift_id) REFERENCES branch_shift (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_history_problem_task ADD CONSTRAINT FK_CF8A0590D19302F8 FOREIGN KEY (assignment_id) REFERENCES `assignment` (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE assignment_history_problem_task');
    }
}
