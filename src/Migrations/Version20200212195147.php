<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212195147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gruposAsignatura DROP FOREIGN KEY fk_gruposAsignatura_1');
        $this->addSql('ALTER TABLE eventos DROP FOREIGN KEY FK_6B23BD8FE52B6C4E');
        $this->addSql('ALTER TABLE puesto DROP FOREIGN KEY fk_puesto_1');
        $this->addSql('ALTER TABLE recurso_user DROP FOREIGN KEY recurso');
        $this->addSql('ALTER TABLE fechasEvento DROP FOREIGN KEY FK_707B6C3187A5F842');
        $this->addSql('ALTER TABLE espacio DROP FOREIGN KEY fk_espacio_1');
        $this->addSql('ALTER TABLE medio DROP FOREIGN KEY fk_medio_1');
        $this->addSql('ALTER TABLE asignaturas DROP FOREIGN KEY FK_6740636AF471CF55');
        $this->addSql('ALTER TABLE eventos DROP FOREIGN KEY FK_6B23BD8FA76ED395');
        $this->addSql('ALTER TABLE recurso_user DROP FOREIGN KEY validadores');
        $this->addSql('DROP TABLE asignaturas');
        $this->addSql('DROP TABLE atencionEventos');
        $this->addSql('DROP TABLE espacio');
        $this->addSql('DROP TABLE eventos');
        $this->addSql('DROP TABLE fechasEvento');
        $this->addSql('DROP TABLE grupo_asignatura_profesor');
        $this->addSql('DROP TABLE gruposAsignatura');
        $this->addSql('DROP TABLE medio');
        $this->addSql('DROP TABLE notificaciones');
        $this->addSql('DROP TABLE profesores');
        $this->addSql('DROP TABLE puesto');
        $this->addSql('DROP TABLE recurso_user');
        $this->addSql('DROP TABLE taxonomia_recursos');
        $this->addSql('DROP TABLE titulaciones');
        $this->addSql('DROP TABLE users');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE asignaturas (id INT AUTO_INCREMENT NOT NULL, titulacion_id INT DEFAULT NULL, codigo VARCHAR(45) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, asignatura VARCHAR(512) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, cuatrimestre VARCHAR(45) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, curso VARCHAR(45) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX fk_asignaturas_1_idx (titulacion_id), UNIQUE INDEX codigo_UNIQUE (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE atencionEventos (id INT UNSIGNED AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, evento_idSerie VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_spanish_ci`, evento_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, tecnico_id INT UNSIGNED NOT NULL, momento DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, observaciones TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_spanish_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE espacio (id INT AUTO_INCREMENT NOT NULL, taxonomia_id INT DEFAULT NULL, nombre VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_spanish_ci`, descripcion TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_spanish_ci`, acl VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_spanish_ci`, disabled TINYINT(1) DEFAULT NULL, aforomaximo INT DEFAULT NULL, aforoexamen INT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX fk_espacio_1_idx (taxonomia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE eventos (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, recurso_id INT DEFAULT NULL, titulo VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, actividad VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, asignatura VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, profesor VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, fechaFin DATE DEFAULT NULL, fechaInicio DATE DEFAULT NULL, horaInicio TIME NOT NULL, horaFin TIME NOT NULL, repeticion INT DEFAULT NULL, diasSemana VARCHAR(256) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, evento_id VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, reservadoPor_id INT UNSIGNED DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, fechaEvento DATE DEFAULT NULL, dia VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, estado VARCHAR(256) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, atendida TINYINT(1) DEFAULT NULL, INDEX recurso_id (recurso_id), INDEX eventos_user_id_foreign (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE fechasEvento (id INT AUTO_INCREMENT NOT NULL, evento_id INT UNSIGNED DEFAULT NULL, fechaEvento DATETIME DEFAULT NULL, INDEX fk_fechaEventos_1_idx (evento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grupo_asignatura_profesor (id INT AUTO_INCREMENT NOT NULL, grupo_asignatura_id INT DEFAULT NULL, profesor_id VARCHAR(45) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE gruposAsignatura (id INT AUTO_INCREMENT NOT NULL, asignatura_id INT DEFAULT NULL, grupo VARCHAR(512) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, capacidad VARCHAR(512) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX fk_gruposAsignatura_1_idx (asignatura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE medio (id INT AUTO_INCREMENT NOT NULL, taxonomia_id INT DEFAULT NULL, nombre VARCHAR(512) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, descripcion VARCHAR(1024) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX fk_medio_1_idx (taxonomia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE notificaciones (id INT AUTO_INCREMENT NOT NULL, msg VARCHAR(524) CHARACTER SET utf8 NOT NULL COLLATE `utf8_spanish_ci`, target INT NOT NULL, estado VARCHAR(512) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, source VARCHAR(256) CHARACTER SET utf8 NOT NULL COLLATE `utf8_spanish_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE profesores (id INT AUTO_INCREMENT NOT NULL, profesor VARCHAR(256) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, DNI VARCHAR(45) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE puesto (id INT AUTO_INCREMENT NOT NULL, espacio_id INT DEFAULT NULL, nombre VARCHAR(512) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, descripcion VARCHAR(45) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, disabled VARCHAR(45) CHARACTER SET latin1 DEFAULT \'FALSE\' COLLATE `latin1_swedish_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX fk_puesto_1_idx (espacio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE recurso_user (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, recurso_id INT DEFAULT NULL, mail TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX user_id (user_id), INDEX recurso_id (recurso_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE taxonomia_recursos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(256) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci` COMMENT \'Agrupación de recursos, por ejemplo, salas de informática, salas de docencia, cámaras de Kanón XL 1200....\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE titulaciones (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(128) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, titulacion VARCHAR(256) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX codigo_UNIQUE (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, dni VARCHAR(12) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, username VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, caducidad DATE DEFAULT NULL, capacidad VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'1 -> usuarios (alumno), 2 -> usuario avanzado (PDI), 3 -> técnico (PAS), 4 -> root, 5 -> validador\', nombre VARCHAR(512) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, apellidos VARCHAR(512) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, email VARCHAR(512) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, colectivo VARCHAR(512) CHARACTER SET utf8 DEFAULT \'Invitado\' COLLATE `utf8_general_ci`, tratamiento VARCHAR(64) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, telefono VARCHAR(32) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, remember_token VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, estado TINYINT(1) DEFAULT NULL, observaciones VARCHAR(512) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, UNIQUE INDEX dni (dni), UNIQUE INDEX users_username_unique (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE asignaturas ADD CONSTRAINT FK_6740636AF471CF55 FOREIGN KEY (titulacion_id) REFERENCES titulaciones (id)');
        $this->addSql('ALTER TABLE espacio ADD CONSTRAINT fk_espacio_1 FOREIGN KEY (taxonomia_id) REFERENCES taxonomia_recursos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE eventos ADD CONSTRAINT FK_6B23BD8FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE eventos ADD CONSTRAINT FK_6B23BD8FE52B6C4E FOREIGN KEY (recurso_id) REFERENCES espacio (id)');
        $this->addSql('ALTER TABLE fechasEvento ADD CONSTRAINT FK_707B6C3187A5F842 FOREIGN KEY (evento_id) REFERENCES eventos (id)');
        $this->addSql('ALTER TABLE gruposAsignatura ADD CONSTRAINT fk_gruposAsignatura_1 FOREIGN KEY (asignatura_id) REFERENCES asignaturas (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE medio ADD CONSTRAINT fk_medio_1 FOREIGN KEY (taxonomia_id) REFERENCES taxonomia_recursos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE puesto ADD CONSTRAINT fk_puesto_1 FOREIGN KEY (espacio_id) REFERENCES espacio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE recurso_user ADD CONSTRAINT recurso FOREIGN KEY (recurso_id) REFERENCES espacio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE recurso_user ADD CONSTRAINT validadores FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
