<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212191713 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_grupo_asignatura ADD sgr_asignatura_id INT NOT NULL');
        $this->addSql('ALTER TABLE sgr_grupo_asignatura ADD CONSTRAINT FK_703677DF2F8F4F37 FOREIGN KEY (sgr_asignatura_id) REFERENCES sgr_asignatura (id)');
        $this->addSql('CREATE INDEX IDX_703677DF2F8F4F37 ON sgr_grupo_asignatura (sgr_asignatura_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sgr_grupo_asignatura DROP FOREIGN KEY FK_703677DF2F8F4F37');
        $this->addSql('DROP INDEX IDX_703677DF2F8F4F37 ON sgr_grupo_asignatura');
        $this->addSql('ALTER TABLE sgr_grupo_asignatura DROP sgr_asignatura_id');
    }
}
