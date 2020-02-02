<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202183424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_espacio ADD taxonomia_id INT NOT NULL');
        $this->addSql('ALTER TABLE sgr_espacio ADD CONSTRAINT FK_60FC25F429CAB57C FOREIGN KEY (taxonomia_id) REFERENCES sgr_taxonomia_espacio (id)');
        $this->addSql('CREATE INDEX IDX_60FC25F429CAB57C ON sgr_espacio (taxonomia_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_espacio DROP FOREIGN KEY FK_60FC25F429CAB57C');
        $this->addSql('DROP INDEX IDX_60FC25F429CAB57C ON sgr_espacio');
        $this->addSql('ALTER TABLE sgr_espacio DROP taxonomia_id');
        
    }
}
