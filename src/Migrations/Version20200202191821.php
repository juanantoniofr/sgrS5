<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202191821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_taxonomia_espacio CHANGE desccripcion descripcion LONGTEXT DEFAULT NULL');
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asignaturas DROP FOREIGN KEY FK_6740636AF471CF55');
        $this->addSql('ALTER TABLE asignaturas ADD CONSTRAINT fk_titulaciones FOREIGN KEY (titulacion_id) REFERENCES titulaciones (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE espacio CHANGE disabled disabled TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE eventos DROP FOREIGN KEY FK_6B23BD8FE52B6C4E');
        $this->addSql('ALTER TABLE eventos DROP FOREIGN KEY FK_6B23BD8FA76ED395');
        $this->addSql('ALTER TABLE eventos CHANGE id id INT UNSIGNED NOT NULL, CHANGE recurso_id recurso_id INT NOT NULL, CHANGE user_id user_id INT UNSIGNED NOT NULL, CHANGE atendida atendida TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE gruposAsignatura CHANGE asignatura_id asignatura_id INT NOT NULL');
        $this->addSql('ALTER TABLE puesto CHANGE espacio_id espacio_id INT NOT NULL');
        $this->addSql('ALTER TABLE recurso_user CHANGE recurso_id recurso_id INT NOT NULL, CHANGE user_id user_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE sgr_taxonomia_espacio CHANGE descripcion desccripcion LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE users CHANGE capacidad capacidad VARCHAR(100) CHARACTER SET utf8 DEFAULT \'0\' NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'1 -> usuarios (alumno), 2 -> usuario avanzado (PDI), 3 -> técnico (PAS), 4 -> root, 5 -> validador\', CHANGE estado estado TINYINT(1) DEFAULT \'0\', CHANGE observaciones observaciones VARCHAR(512) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
