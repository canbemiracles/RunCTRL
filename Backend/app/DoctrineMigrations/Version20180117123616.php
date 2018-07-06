<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180117123616 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE time_format (id INT AUTO_INCREMENT NOT NULL, time_format VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE assignment_message');
        $this->addSql('DROP TABLE assignment_notification');
        $this->addSql('ALTER TABLE company ADD time_format_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F445FFDDD FOREIGN KEY (time_format_id) REFERENCES time_format (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_4FBF094F445FFDDD ON company (time_format_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F445FFDDD');
        $this->addSql('CREATE TABLE assignment_message (id INT NOT NULL, description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignment_notification (id INT NOT NULL, description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment_message ADD CONSTRAINT FK_D5837D86BF396750 FOREIGN KEY (id) REFERENCES assignment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_notification ADD CONSTRAINT FK_F32B34F8BF396750 FOREIGN KEY (id) REFERENCES assignment (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE time_format');
        $this->addSql('DROP INDEX IDX_4FBF094F445FFDDD ON company');
        $this->addSql('ALTER TABLE company DROP time_format_id');
    }
}
