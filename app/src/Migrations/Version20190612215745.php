<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612215745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photos_tags ADD PRIMARY KEY (photo_id, tag_id)');
        $this->addSql('ALTER TABLE photos_tags ADD CONSTRAINT FK_F8A626C77E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F8A626C77E9E4C8C ON photos_tags (photo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photos_tags DROP FOREIGN KEY FK_F8A626C77E9E4C8C');
        $this->addSql('DROP INDEX IDX_F8A626C77E9E4C8C ON photos_tags');
        $this->addSql('ALTER TABLE photos_tags DROP PRIMARY KEY');
    }
}
