<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209090322 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fechasEvento ADD CONSTRAINT FK_707B6C3187A5F842 FOREIGN KEY (evento_id) REFERENCES eventos (id)');
        $this->addSql('CREATE INDEX fk_fechaEventos_1_idx ON fechasEvento (evento_id)');
        $this->addSql('ALTER TABLE espacio ADD mediosdisponibles VARCHAR(512) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE espacio DROP mediosdisponibles');
        $this->addSql('ALTER TABLE fechasEvento DROP FOREIGN KEY FK_707B6C3187A5F842');
        $this->addSql('DROP INDEX fk_fechaEventos_1_idx ON fechasEvento');
    }
}
