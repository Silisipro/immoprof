<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426153500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_bien DROP FOREIGN KEY FK_92E2068EBD95B80F');
        $this->addSql('DROP INDEX IDX_92E2068EBD95B80F ON type_bien');
        $this->addSql('ALTER TABLE type_bien DROP bien_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_bien ADD bien_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_bien ADD CONSTRAINT FK_92E2068EBD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('CREATE INDEX IDX_92E2068EBD95B80F ON type_bien (bien_id)');
    }
}
