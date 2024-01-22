<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108205858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (payment_id INT AUTO_INCREMENT NOT NULL, payment_name VARCHAR(50) NOT NULL, PRIMARY KEY(payment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `Order` ADD payment_id INT NOT NULL');
        $this->addSql('ALTER TABLE `Order` ADD CONSTRAINT FK_34E8BC9C4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (payment_id)');
        $this->addSql('CREATE INDEX IDX_34E8BC9C4C3A3BB ON `Order` (payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `Order` DROP FOREIGN KEY FK_34E8BC9C4C3A3BB');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP INDEX IDX_34E8BC9C4C3A3BB ON `Order`');
        $this->addSql('ALTER TABLE `Order` DROP payment_id');
    }
}
