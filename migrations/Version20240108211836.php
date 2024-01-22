<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108211836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B74C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (payment_id)');
        $this->addSql('CREATE INDEX IDX_BA388B74C3A3BB ON cart (payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B74C3A3BB');
        $this->addSql('DROP INDEX IDX_BA388B74C3A3BB ON cart');
        $this->addSql('ALTER TABLE cart DROP payment_id');
    }
}
