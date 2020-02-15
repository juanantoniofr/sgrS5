<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200215090923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sgr_tipo_actividad (id INT AUTO_INCREMENT NOT NULL, actividad VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sgr_evento ADD actividad_id INT NOT NULL');
        $this->addSql('ALTER TABLE sgr_evento ADD CONSTRAINT FK_B5A1F956014FACA FOREIGN KEY (actividad_id) REFERENCES sgr_tipo_actividad (id)');
        $this->addSql('CREATE INDEX IDX_B5A1F956014FACA ON sgr_evento (actividad_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_evento DROP FOREIGN KEY FK_B5A1F956014FACA');
        $this->addSql('DROP TABLE sgr_tipo_actividad');
        $this->addSql('DROP INDEX IDX_B5A1F956014FACA ON sgr_evento');
        $this->addSql('ALTER TABLE sgr_evento DROP actividad_id');
    }
}
