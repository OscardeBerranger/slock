<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122123319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE friend_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE group_conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE groupe_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_chat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE friend_request (id INT NOT NULL, sent_by_id INT NOT NULL, received_by_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F284D94A45BB98C ON friend_request (sent_by_id)');
        $this->addSql('CREATE INDEX IDX_F284D946F8DDD17 ON friend_request (received_by_id)');
        $this->addSql('COMMENT ON COLUMN friend_request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE group_conversation (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE group_conversation_profile (group_conversation_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(group_conversation_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_54DD05CBB73F9E4F ON group_conversation_profile (group_conversation_id)');
        $this->addSql('CREATE INDEX IDX_54DD05CBCCFA12B8 ON group_conversation_profile (profile_id)');
        $this->addSql('CREATE TABLE group_recipient_conv_profile (group_conversation_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(group_conversation_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_E882A37AB73F9E4F ON group_recipient_conv_profile (group_conversation_id)');
        $this->addSql('CREATE INDEX IDX_E882A37ACCFA12B8 ON group_recipient_conv_profile (profile_id)');
        $this->addSql('CREATE TABLE groupe_message (id INT NOT NULL, group_conversation_id INT NOT NULL, author_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70DAD731B73F9E4F ON groupe_message (group_conversation_id)');
        $this->addSql('CREATE INDEX IDX_70DAD731F675F31B ON groupe_message (author_id)');
        $this->addSql('COMMENT ON COLUMN groupe_message.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, private_message_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045F5EBFB95E ON image (private_message_id)');
        $this->addSql('COMMENT ON COLUMN image.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE private_chat (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE private_conversation (id INT NOT NULL, conv_creator_id INT NOT NULL, conv_recipient_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCF38EEB855AA6A ON private_conversation (conv_creator_id)');
        $this->addSql('CREATE INDEX IDX_DCF38EEB5FDB19BC ON private_conversation (conv_recipient_id)');
        $this->addSql('CREATE TABLE private_message (id INT NOT NULL, private_conversation_id INT NOT NULL, author_id INT NOT NULL, content VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4744FC9B4242ECCE ON private_message (private_conversation_id)');
        $this->addSql('CREATE INDEX IDX_4744FC9BF675F31B ON private_message (author_id)');
        $this->addSql('COMMENT ON COLUMN private_message.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, of_user_id INT NOT NULL, username VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0F5A1B2224 ON profile (of_user_id)');
        $this->addSql('COMMENT ON COLUMN profile.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE relation (id INT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62894749F624B39D ON relation (sender_id)');
        $this->addSql('CREATE INDEX IDX_62894749E92F8F78 ON relation (recipient_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
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
        $this->addSql('ALTER TABLE group_conversation_profile ADD CONSTRAINT FK_54DD05CBB73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation_profile ADD CONSTRAINT FK_54DD05CBCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_recipient_conv_profile ADD CONSTRAINT FK_E882A37AB73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_recipient_conv_profile ADD CONSTRAINT FK_E882A37ACCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_message ADD CONSTRAINT FK_70DAD731B73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_message ADD CONSTRAINT FK_70DAD731F675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F5EBFB95E FOREIGN KEY (private_message_id) REFERENCES private_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEB855AA6A FOREIGN KEY (conv_creator_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEB5FDB19BC FOREIGN KEY (conv_recipient_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B4242ECCE FOREIGN KEY (private_conversation_id) REFERENCES private_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9BF675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F5A1B2224 FOREIGN KEY (of_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749F624B39D FOREIGN KEY (sender_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749E92F8F78 FOREIGN KEY (recipient_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE friend_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE group_conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE groupe_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_chat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE friend_request DROP CONSTRAINT FK_F284D94A45BB98C');
        $this->addSql('ALTER TABLE friend_request DROP CONSTRAINT FK_F284D946F8DDD17');
        $this->addSql('ALTER TABLE group_conversation_profile DROP CONSTRAINT FK_54DD05CBB73F9E4F');
        $this->addSql('ALTER TABLE group_conversation_profile DROP CONSTRAINT FK_54DD05CBCCFA12B8');
        $this->addSql('ALTER TABLE group_recipient_conv_profile DROP CONSTRAINT FK_E882A37AB73F9E4F');
        $this->addSql('ALTER TABLE group_recipient_conv_profile DROP CONSTRAINT FK_E882A37ACCFA12B8');
        $this->addSql('ALTER TABLE groupe_message DROP CONSTRAINT FK_70DAD731B73F9E4F');
        $this->addSql('ALTER TABLE groupe_message DROP CONSTRAINT FK_70DAD731F675F31B');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F5EBFB95E');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEB855AA6A');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEB5FDB19BC');
        $this->addSql('ALTER TABLE private_message DROP CONSTRAINT FK_4744FC9B4242ECCE');
        $this->addSql('ALTER TABLE private_message DROP CONSTRAINT FK_4744FC9BF675F31B');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0F5A1B2224');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749F624B39D');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749E92F8F78');
        $this->addSql('DROP TABLE friend_request');
        $this->addSql('DROP TABLE group_conversation');
        $this->addSql('DROP TABLE group_conversation_profile');
        $this->addSql('DROP TABLE group_recipient_conv_profile');
        $this->addSql('DROP TABLE groupe_message');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE private_chat');
        $this->addSql('DROP TABLE private_conversation');
        $this->addSql('DROP TABLE private_message');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
