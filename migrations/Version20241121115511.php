<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121115511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rubric ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rubric ADD CONSTRAINT FK_60C4016C727ACA70 FOREIGN KEY (parent_id) REFERENCES rubric (id)');
        $this->addSql('CREATE INDEX IDX_60C4016C727ACA70 ON rubric (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rubric DROP FOREIGN KEY FK_60C4016C727ACA70');
        $this->addSql('DROP INDEX IDX_60C4016C727ACA70 ON rubric');
        $this->addSql('ALTER TABLE rubric DROP parent_id');
    }
}
