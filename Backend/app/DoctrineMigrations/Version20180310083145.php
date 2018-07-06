<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310083145 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE history_branch_manager_work (id INT AUTO_INCREMENT NOT NULL, branch_manager_id INT DEFAULT NULL, branch_shift_id INT DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME DEFAULT NULL, INDEX IDX_CAA1276067E63D3D (branch_manager_id), INDEX IDX_CAA127605CD365CD (branch_shift_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history_branch_manager_work ADD CONSTRAINT FK_CAA1276067E63D3D FOREIGN KEY (branch_manager_id) REFERENCES user_branch_manager (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE history_branch_manager_work ADD CONSTRAINT FK_CAA127605CD365CD FOREIGN KEY (branch_shift_id) REFERENCES branch_shift (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE history_branch_manager_work');
    }
}
