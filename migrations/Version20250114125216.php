<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114125216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery ADD ref VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE payment_date payment_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE type type VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_REF ON `order` (ref)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery DROP ref');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_REF ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE payment_date payment_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE type type VARCHAR(255) NOT NULL');
    }
}
