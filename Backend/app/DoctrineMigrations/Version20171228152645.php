<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171228152645 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE u_t_c (id INT AUTO_INCREMENT NOT NULL, time_zone_id INT DEFAULT NULL, value VARCHAR(30) NOT NULL, INDEX IDX_DE1E36A3CBAB9ECD (time_zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE u_t_c ADD CONSTRAINT FK_DE1E36A3CBAB9ECD FOREIGN KEY (time_zone_id) REFERENCES time_zone (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE time_zone ADD abbr VARCHAR(30) NOT NULL, ADD isdst TINYINT(1) NOT NULL, ADD text VARCHAR(80) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE u_t_c');
        $this->addSql('ALTER TABLE time_zone DROP abbr, DROP isdst, DROP text');
    }
}
