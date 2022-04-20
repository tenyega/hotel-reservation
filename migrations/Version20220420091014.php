<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220420091014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone INT NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, reservation_id_id INT NOT NULL, total_charges DOUBLE PRECISION NOT NULL, payment_type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, coupon_promo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6D28840D3C3B4EF0 (reservation_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, booking_date DATETIME NOT NULL, check_in_date DATETIME NOT NULL, check_out_date DATETIME NOT NULL, number_of_beds INT NOT NULL, special_demande VARCHAR(255) NOT NULL, room_no INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, room_no INT NOT NULL, floor INT NOT NULL, max_capacity INT NOT NULL, type VARCHAR(255) NOT NULL, is_smoking TINYINT(1) NOT NULL, facility_possible VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D3C3B4EF0 FOREIGN KEY (reservation_id_id) REFERENCES reservation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D3C3B4EF0');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE room');
    }
}
