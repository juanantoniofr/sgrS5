<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200204173837 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sgr_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, dni VARCHAR(32) DEFAULT NULL, caducidad DATETIME NOT NULL, nombre VARCHAR(255) DEFAULT NULL, apellidos VARCHAR(512) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, colectivo VARCHAR(255) NOT NULL, observaciones LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_5992C6F4F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eventos CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE user_id user_id INT UNSIGNED DEFAULT NULL, CHANGE recurso_id recurso_id INT DEFAULT NULL, CHANGE atendida atendida TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE eventos ADD CONSTRAINT FK_6B23BD8FE52B6C4E FOREIGN KEY (recurso_id) REFERENCES espacio (id)');
        $this->addSql('ALTER TABLE eventos ADD CONSTRAINT FK_6B23BD8FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE recurso_user CHANGE user_id user_id INT UNSIGNED DEFAULT NULL, CHANGE recurso_id recurso_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE asignaturas DROP FOREIGN KEY fk_titulaciones');
        $this->addSql('ALTER TABLE asignaturas ADD CONSTRAINT FK_6740636AF471CF55 FOREIGN KEY (titulacion_id) REFERENCES titulaciones (id)');
        $this->addSql('ALTER TABLE puesto CHANGE espacio_id espacio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gruposAsignatura CHANGE asignatura_id asignatura_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE capacidad capacidad VARCHAR(100) NOT NULL COMMENT \'1 -> usuarios (alumno), 2 -> usuario avanzado (PDI), 3 -> técnico (PAS), 4 -> root, 5 -> validador\', CHANGE estado estado TINYINT(1) DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(512) NOT NULL');
        $this->addSql('ALTER TABLE espacio CHANGE disabled disabled TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sgr_user');
        $this->addSql('ALTER TABLE asignaturas DROP FOREIGN KEY FK_6740636AF471CF55');
        $this->addSql('ALTER TABLE asignaturas ADD CONSTRAINT fk_titulaciones FOREIGN KEY (titulacion_id) REFERENCES titulaciones (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE espacio CHANGE disabled disabled TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE eventos DROP FOREIGN KEY FK_6B23BD8FE52B6C4E');
        $this->addSql('ALTER TABLE eventos DROP FOREIGN KEY FK_6B23BD8FA76ED395');
        $this->addSql('ALTER TABLE eventos CHANGE id id INT UNSIGNED NOT NULL, CHANGE recurso_id recurso_id INT NOT NULL, CHANGE user_id user_id INT UNSIGNED NOT NULL, CHANGE atendida atendida TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE gruposAsignatura CHANGE asignatura_id asignatura_id INT NOT NULL');
        $this->addSql('ALTER TABLE puesto CHANGE espacio_id espacio_id INT NOT NULL');
        $this->addSql('ALTER TABLE recurso_user CHANGE recurso_id recurso_id INT NOT NULL, CHANGE user_id user_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE capacidad capacidad VARCHAR(100) CHARACTER SET utf8 DEFAULT \'0\' NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'1 -> usuarios (alumno), 2 -> usuario avanzado (PDI), 3 -> técnico (PAS), 4 -> root, 5 -> validador\', CHANGE estado estado TINYINT(1) DEFAULT \'0\', CHANGE observaciones observaciones VARCHAR(512) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
