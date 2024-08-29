<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240813171930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE round (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, player_id INT DEFAULT NULL, goal VARCHAR(255) NOT NULL, INDEX IDX_C5EEEA3454177093 (room_id), INDEX IDX_C5EEEA3499E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA3454177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA3499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA3454177093');
        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA3499E6F5DF');
        $this->addSql('DROP TABLE round');
    }
}
