<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212191458 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_asignatura ADD sgr_titulacion_id INT NOT NULL');
        $this->addSql('ALTER TABLE sgr_asignatura ADD CONSTRAINT FK_66CBBB6E1E398C39 FOREIGN KEY (sgr_titulacion_id) REFERENCES sgr_titulacion (id)');
        $this->addSql('CREATE INDEX IDX_66CBBB6E1E398C39 ON sgr_asignatura (sgr_titulacion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_asignatura DROP FOREIGN KEY FK_66CBBB6E1E398C39');
        $this->addSql('DROP INDEX IDX_66CBBB6E1E398C39 ON sgr_asignatura');
        $this->addSql('ALTER TABLE sgr_asignatura DROP sgr_titulacion_id');
    }
}
