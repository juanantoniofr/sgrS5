<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200215083245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sgr_evento (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, espacio_id INT DEFAULT NULL, asignatura_id INT DEFAULT NULL, profesor_id INT DEFAULT NULL, grupo_asignatura_id INT DEFAULT NULL, titulacion_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, actividad VARCHAR(255) NOT NULL, estado VARCHAR(255) NOT NULL, periodica TINYINT(1) NOT NULL, f_inicio DATE NOT NULL, f_fin DATE NOT NULL, h_inicio TIME NOT NULL, h_fin TIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B5A1F95A76ED395 (user_id), INDEX IDX_B5A1F957CFC1D2C (espacio_id), INDEX IDX_B5A1F95C5C70C5B (asignatura_id), INDEX IDX_B5A1F95E52BD977 (profesor_id), INDEX IDX_B5A1F95D5E005F9 (grupo_asignatura_id), INDEX IDX_B5A1F95F471CF55 (titulacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sgr_evento ADD CONSTRAINT FK_B5A1F95A76ED395 FOREIGN KEY (user_id) REFERENCES sgr_user (id)');
        $this->addSql('ALTER TABLE sgr_evento ADD CONSTRAINT FK_B5A1F957CFC1D2C FOREIGN KEY (espacio_id) REFERENCES sgr_espacio (id)');
        $this->addSql('ALTER TABLE sgr_evento ADD CONSTRAINT FK_B5A1F95C5C70C5B FOREIGN KEY (asignatura_id) REFERENCES sgr_asignatura (id)');
        $this->addSql('ALTER TABLE sgr_evento ADD CONSTRAINT FK_B5A1F95E52BD977 FOREIGN KEY (profesor_id) REFERENCES sgr_profesor (id)');
        $this->addSql('ALTER TABLE sgr_evento ADD CONSTRAINT FK_B5A1F95D5E005F9 FOREIGN KEY (grupo_asignatura_id) REFERENCES sgr_grupo_asignatura (id)');
        $this->addSql('ALTER TABLE sgr_evento ADD CONSTRAINT FK_B5A1F95F471CF55 FOREIGN KEY (titulacion_id) REFERENCES sgr_titulacion (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sgr_evento');
    }
}
