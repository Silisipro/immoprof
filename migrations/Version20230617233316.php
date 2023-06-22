<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617233316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien ADD reference VARCHAR(255) DEFAULT NULL, CHANGE surface surface INT DEFAULT NULL, CHANGE rooms rooms INT DEFAULT NULL, CHANGE bedrooms bedrooms INT DEFAULT NULL, CHANGE floor floor INT DEFAULT NULL, CHANGE heat heat INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP reference, CHANGE surface surface INT NOT NULL, CHANGE rooms rooms INT NOT NULL, CHANGE bedrooms bedrooms INT NOT NULL, CHANGE floor floor INT NOT NULL, CHANGE heat heat INT NOT NULL');
    }
}
