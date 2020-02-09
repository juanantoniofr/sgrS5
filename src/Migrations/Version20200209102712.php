<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209102712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sgr_espacio_sgr_equipamiento (sgr_espacio_id INT NOT NULL, sgr_equipamiento_id INT NOT NULL, INDEX IDX_187E7FEB8874708C (sgr_espacio_id), INDEX IDX_187E7FEB5A5FBDB5 (sgr_equipamiento_id), PRIMARY KEY(sgr_espacio_id, sgr_equipamiento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sgr_espacio_sgr_equipamiento ADD CONSTRAINT FK_187E7FEB8874708C FOREIGN KEY (sgr_espacio_id) REFERENCES sgr_espacio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sgr_espacio_sgr_equipamiento ADD CONSTRAINT FK_187E7FEB5A5FBDB5 FOREIGN KEY (sgr_equipamiento_id) REFERENCES sgr_equipamiento (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sgr_espacio_sgr_equipamiento');
    }
}
