<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118084839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, adr_type VARCHAR(20) NOT NULL, adr_city VARCHAR(100) NOT NULL, adr_address VARCHAR(255) NOT NULL, adr_cp INT NOT NULL, is_default TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, prod_img VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, prod_label VARCHAR(100) NOT NULL, prod_slug VARCHAR(100) NOT NULL, prod_ref VARCHAR(50) NOT NULL, prod_desc LONGTEXT NOT NULL, prod_price NUMERIC(10, 2) NOT NULL, prod_stock INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_PRODUCT_REF (prod_ref), UNIQUE INDEX UNIQ_PRODUCT_SLUG (prod_slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rubric (id INT AUTO_INCREMENT NOT NULL, rub_label VARCHAR(100) NOT NULL, rub_slug VARCHAR(100) NOT NULL, rub_desc LONGTEXT NOT NULL, rub_img VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_RUBRIC_SLUG (rub_slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, serv_type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_details (id INT AUTO_INCREMENT NOT NULL, spl_siret VARCHAR(20) NOT NULL, spl_type VARCHAR(20) NOT NULL, spl_status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_SPL_SIRET (spl_siret), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, user_ref VARCHAR(50) NOT NULL, user_firstname VARCHAR(100) NOT NULL, user_lastname VARCHAR(100) NOT NULL, user_phone VARCHAR(20) NOT NULL, coef NUMERIC(8, 2) NOT NULL, user_siret VARCHAR(20) DEFAULT NULL, user_last_conn DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), UNIQUE INDEX UNIQ_USER_REF (user_ref), UNIQUE INDEX UNIQ_USER_SIRET (user_siret), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE rubric');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE supplier_details');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
