<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260328193122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_admissions (id VARCHAR(36) NOT NULL, patient_id VARCHAR(36) NOT NULL, hospital_unit_code VARCHAR(20) NOT NULL, admitted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, created_by VARCHAR(36) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_patient_admissions_patient_id ON patient_admissions (patient_id)');
        $this->addSql('CREATE INDEX idx_patient_admissions_status ON patient_admissions (status)');
        $this->addSql('CREATE UNIQUE INDEX uniq_patient_admission_id ON patient_admissions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE patient_admissions');
    }
}
