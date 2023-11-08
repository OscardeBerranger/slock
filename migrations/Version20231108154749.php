<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108154749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE friend_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_chat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE friend_request (id INT NOT NULL, sent_by_id INT NOT NULL, received_by_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F284D94A45BB98C ON friend_request (sent_by_id)');
        $this->addSql('CREATE INDEX IDX_F284D946F8DDD17 ON friend_request (received_by_id)');
        $this->addSql('COMMENT ON COLUMN friend_request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE private_chat (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, of_user_id INT NOT NULL, username VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0F5A1B2224 ON profile (of_user_id)');
        $this->addSql('COMMENT ON COLUMN profile.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE relation (id INT NOT NULL, relation_as_sender_id INT NOT NULL, relation_as_recipient_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62894749AD6D0CE ON relation (relation_as_sender_id)');
        $this->addSql('CREATE INDEX IDX_628947499AB6B439 ON relation (relation_as_recipient_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE friend_request ADD CONSTRAINT FK_F284D94A45BB98C FOREIGN KEY (sent_by_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friend_request ADD CONSTRAINT FK_F284D946F8DDD17 FOREIGN KEY (received_by_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F5A1B2224 FOREIGN KEY (of_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749AD6D0CE FOREIGN KEY (relation_as_sender_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_628947499AB6B439 FOREIGN KEY (relation_as_recipient_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE friend_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_chat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE friend_request DROP CONSTRAINT FK_F284D94A45BB98C');
        $this->addSql('ALTER TABLE friend_request DROP CONSTRAINT FK_F284D946F8DDD17');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0F5A1B2224');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749AD6D0CE');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_628947499AB6B439');
        $this->addSql('DROP TABLE friend_request');
        $this->addSql('DROP TABLE private_chat');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
