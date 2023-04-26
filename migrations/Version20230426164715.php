<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426164715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien ADD standing_id INT NOT NULL');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC386346DAB42 FOREIGN KEY (standing_id) REFERENCES standing (id)');
        $this->addSql('CREATE INDEX IDX_45EDC386346DAB42 ON bien (standing_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC386346DAB42');
        $this->addSql('DROP INDEX IDX_45EDC386346DAB42 ON bien');
        $this->addSql('ALTER TABLE bien DROP standing_id');
    }
}
