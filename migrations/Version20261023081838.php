<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261023081838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, recette_id INT NOT NULL, INDEX IDX_389B78389312FE9 (recette_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B78389312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD auteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_49BB639060BB6FE6 ON recette (auteur_id)');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B78389312FE9');
        $this->addSql('DROP TABLE tag');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639060BB6FE6');
        $this->addSql('DROP INDEX IDX_49BB639060BB6FE6 ON recette');
        $this->addSql('ALTER TABLE recette DROP auteur_id');
        $this->addSql('ALTER TABLE user DROP is_verified');
    }
}
