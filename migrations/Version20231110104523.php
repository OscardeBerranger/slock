<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110104523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE private_message ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE private_message ADD content VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE private_message ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN private_message.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9BF675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4744FC9BF675F31B ON private_message (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE private_message DROP CONSTRAINT FK_4744FC9BF675F31B');
        $this->addSql('DROP INDEX IDX_4744FC9BF675F31B');
        $this->addSql('ALTER TABLE private_message DROP author_id');
        $this->addSql('ALTER TABLE private_message DROP content');
        $this->addSql('ALTER TABLE private_message DROP created_at');
    }
}
