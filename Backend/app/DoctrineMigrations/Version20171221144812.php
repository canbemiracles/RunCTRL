<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171221144812 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employees_branch_roles (employee_id INT NOT NULL, abstract_branch_station_role_id INT NOT NULL, INDEX IDX_13B7D3778C03F15C (employee_id), INDEX IDX_13B7D3771B199C48 (abstract_branch_station_role_id), PRIMARY KEY(employee_id, abstract_branch_station_role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employees_branch_roles ADD CONSTRAINT FK_13B7D3778C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employees_branch_roles ADD CONSTRAINT FK_13B7D3771B199C48 FOREIGN KEY (abstract_branch_station_role_id) REFERENCES branch_station_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee ADD social_security_number VARCHAR(32) DEFAULT NULL, ADD bonus DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) NOT NULL');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE employees_branch_roles');
        $this->addSql('ALTER TABLE branch_station_role CHANGE color color VARCHAR(6) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE employee DROP social_security_number, DROP bonus');
        $this->addSql('ALTER TABLE sessions CHANGE sess_id sess_id VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
    }
}
