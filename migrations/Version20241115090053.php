<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115090053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD user_ref VARCHAR(50) NOT NULL, ADD user_firstname VARCHAR(100) NOT NULL, ADD user_lastname VARCHAR(100) NOT NULL, ADD user_phone VARCHAR(20) NOT NULL, ADD coef NUMERIC(8, 2) NOT NULL, ADD user_siret VARCHAR(20) DEFAULT NULL, ADD user_last_conn DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP user_ref, DROP user_firstname, DROP user_lastname, DROP user_phone, DROP coef, DROP user_siret, DROP user_last_conn');
    }
}
