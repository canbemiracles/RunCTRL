<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171120153758 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recommendations_roles (id INT AUTO_INCREMENT NOT NULL, recommendations_station_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, INDEX IDX_3F093F49CAB5C2B (recommendations_station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendations_stations (id INT AUTO_INCREMENT NOT NULL, subcategory_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, INDEX IDX_A738C5F55DC6FE57 (subcategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recommendations_roles ADD CONSTRAINT FK_3F093F49CAB5C2B FOREIGN KEY (recommendations_station_id) REFERENCES recommendations_stations (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE recommendations_stations ADD CONSTRAINT FK_A738C5F55DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recommendations_roles DROP FOREIGN KEY FK_3F093F49CAB5C2B');
        $this->addSql('DROP TABLE recommendations_roles');
        $this->addSql('DROP TABLE recommendations_stations');
    }
}
