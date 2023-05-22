<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522084153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien ADD etat VARCHAR(255) NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE type_bien ADD deleted TINYINT(1) NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD bloque TINYINT(1) NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted TINYINT(1) NOT NULL, DROP update_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP etat, DROP updated_at, DROP deleted');
        $this->addSql('ALTER TABLE user ADD update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP bloque, DROP updated_at, DROP deleted');
        $this->addSql('ALTER TABLE type_bien DROP deleted, CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
