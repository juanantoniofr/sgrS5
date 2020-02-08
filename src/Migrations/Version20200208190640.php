<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200208190640 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_espacio DROP FOREIGN KEY FK_60FC25F429CAB57C');
        $this->addSql('ALTER TABLE sgr_termino DROP FOREIGN KEY FK_61AACC129CAB57C');
        $this->addSql('CREATE TABLE sgr_taxonomia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE sgr_taxonomia_espacio');
       
       // $this->addSql('ALTER TABLE sgr_termino DROP FOREIGN KEY FK_61AACC129CAB57C');
        $this->addSql('ALTER TABLE sgr_termino ADD CONSTRAINT FK_61AACC129CAB57C FOREIGN KEY (taxonomia_id) REFERENCES sgr_taxonomia (id)');
        $this->addSql('DROP INDEX IDX_60FC25F429CAB57C ON sgr_espacio');
        $this->addSql('ALTER TABLE sgr_espacio DROP taxonomia_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_termino DROP FOREIGN KEY FK_61AACC129CAB57C');
        $this->addSql('CREATE TABLE sgr_taxonomia_espacio (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, descripcion LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE sgr_taxonomia');
        $this->addSql('ALTER TABLE sgr_espacio ADD taxonomia_id INT NOT NULL');
        $this->addSql('ALTER TABLE sgr_espacio ADD CONSTRAINT FK_60FC25F429CAB57C FOREIGN KEY (taxonomia_id) REFERENCES sgr_taxonomia_espacio (id)');
       // $this->addSql('CREATE INDEX IDX_60FC25F429CAB57C ON sgr_espacio (taxonomia_id)');
        $this->addSql('ALTER TABLE sgr_termino DROP FOREIGN KEY FK_61AACC129CAB57C');
        $this->addSql('ALTER TABLE sgr_termino ADD CONSTRAINT FK_61AACC129CAB57C FOREIGN KEY (taxonomia_id) REFERENCES sgr_taxonomia_espacio (id)');
    }
}
