<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426163426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE standing DROP FOREIGN KEY FK_619A8AD8BD95B80F');
        $this->addSql('DROP INDEX IDX_619A8AD8BD95B80F ON standing');
        $this->addSql('ALTER TABLE standing DROP bien_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE standing ADD bien_id INT NOT NULL');
        $this->addSql('ALTER TABLE standing ADD CONSTRAINT FK_619A8AD8BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('CREATE INDEX IDX_619A8AD8BD95B80F ON standing (bien_id)');
    }
}
