<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212192016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sgr_profesor_sgr_grupo_asignatura (sgr_profesor_id INT NOT NULL, sgr_grupo_asignatura_id INT NOT NULL, INDEX IDX_25D6E0483309F2F2 (sgr_profesor_id), INDEX IDX_25D6E0484F50CDE5 (sgr_grupo_asignatura_id), PRIMARY KEY(sgr_profesor_id, sgr_grupo_asignatura_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sgr_profesor_sgr_grupo_asignatura ADD CONSTRAINT FK_25D6E0483309F2F2 FOREIGN KEY (sgr_profesor_id) REFERENCES sgr_profesor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sgr_profesor_sgr_grupo_asignatura ADD CONSTRAINT FK_25D6E0484F50CDE5 FOREIGN KEY (sgr_grupo_asignatura_id) REFERENCES sgr_grupo_asignatura (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sgr_profesor_sgr_grupo_asignatura');
    }
}
