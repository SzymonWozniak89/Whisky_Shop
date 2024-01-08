<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240106230116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item ADD order_ref_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527E238517C FOREIGN KEY (order_ref_id) REFERENCES `Order` (order_id)');
        $this->addSql('CREATE INDEX IDX_F0FE2527E238517C ON cart_item (order_ref_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527E238517C');
        $this->addSql('DROP INDEX IDX_F0FE2527E238517C ON cart_item');
        $this->addSql('ALTER TABLE cart_item DROP order_ref_id');
    }
}
