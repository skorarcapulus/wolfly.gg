<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251127132414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id UUID NOT NULL, title VARCHAR(255) NOT NULL, is_released BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, source TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, overlay_id UUID DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_D8698A76F77080E1 ON document (overlay_id)');
        $this->addSql('CREATE TABLE overlay (id UUID NOT NULL, title VARCHAR(255) NOT NULL, is_released BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76F77080E1 FOREIGN KEY (overlay_id) REFERENCES overlay (id) ON DELETE CASCADE NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76F77080E1');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE overlay');
    }
}
