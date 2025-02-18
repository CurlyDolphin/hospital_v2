<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214110903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE hospitalization_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE card_number_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE procedure_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ward_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ward_procedure_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE hospitalization (id INT NOT NULL, patient_id INT NOT NULL, ward_id INT NOT NULL, discharge_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40CF08916B899279 ON hospitalization (patient_id)');
        $this->addSql('CREATE INDEX IDX_40CF0891D95D22FD ON hospitalization (ward_id)');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, name VARCHAR(80) NOT NULL, last_name VARCHAR(80) NOT NULL, gender VARCHAR(6) NOT NULL, is_identified BOOLEAN NOT NULL, birthday DATE DEFAULT NULL, card_number INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EBE4AF4C20 ON patient (card_number)');
        $this->addSql('CREATE TABLE procedure (id INT NOT NULL, name VARCHAR(120) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C3CBC1F5E237E06 ON procedure (name)');
        $this->addSql('CREATE TABLE ward (id INT NOT NULL, ward_number INT NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C96F581BFA80FB69 ON ward (ward_number)');
        $this->addSql('CREATE TABLE ward_procedure (id INT NOT NULL, ward_id INT NOT NULL, procedure_id INT NOT NULL, sequence INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_972AEC3CD95D22FD ON ward_procedure (ward_id)');
        $this->addSql('CREATE INDEX IDX_972AEC3C1624BCD2 ON ward_procedure (procedure_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_ward_procedure ON ward_procedure (ward_id, procedure_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_sequence_in_ward ON ward_procedure (ward_id, sequence)');
        $this->addSql('ALTER TABLE hospitalization ADD CONSTRAINT FK_40CF08916B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hospitalization ADD CONSTRAINT FK_40CF0891D95D22FD FOREIGN KEY (ward_id) REFERENCES ward (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ward_procedure ADD CONSTRAINT FK_972AEC3CD95D22FD FOREIGN KEY (ward_id) REFERENCES ward (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ward_procedure ADD CONSTRAINT FK_972AEC3C1624BCD2 FOREIGN KEY (procedure_id) REFERENCES procedure (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE hospitalization_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE card_number_seq CASCADE');
        $this->addSql('DROP SEQUENCE procedure_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ward_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ward_procedure_id_seq CASCADE');
        $this->addSql('ALTER TABLE hospitalization DROP CONSTRAINT FK_40CF08916B899279');
        $this->addSql('ALTER TABLE hospitalization DROP CONSTRAINT FK_40CF0891D95D22FD');
        $this->addSql('ALTER TABLE ward_procedure DROP CONSTRAINT FK_972AEC3CD95D22FD');
        $this->addSql('ALTER TABLE ward_procedure DROP CONSTRAINT FK_972AEC3C1624BCD2');
        $this->addSql('DROP TABLE hospitalization');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE procedure');
        $this->addSql('DROP TABLE ward');
        $this->addSql('DROP TABLE ward_procedure');
    }
}
