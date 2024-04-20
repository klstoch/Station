<?php

declare(strict_types=1);

namespace Station\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240420142740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Инициализация схемы БД';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE clients (id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(150) NOT NULL, car JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE stations_clients (client_id VARCHAR NOT NULL, station_id VARCHAR NOT NULL, PRIMARY KEY(client_id, station_id))');
        $this->addSql('CREATE INDEX IDX_9CDF64E819EB6921 ON stations_clients (client_id)');
        $this->addSql('CREATE INDEX IDX_9CDF64E821BDB235 ON stations_clients (station_id)');
        $this->addSql('CREATE TABLE employees (id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(150) NOT NULL, grade VARCHAR NOT NULL, time DATE NOT NULL, additional_competences VARCHAR NOT NULL, station_id VARCHAR NOT NULL, speciality VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BA82C30021BDB235 ON employees (station_id)');
        $this->addSql('CREATE TABLE job_contracts (id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, schedule JSON NOT NULL, salary_rate NUMERIC(8, 2) NOT NULL, interest_rate INT NOT NULL, is_active BOOLEAN NOT NULL, employ_id VARCHAR NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CEE04662BC18698D ON job_contracts (employ_id)');
        $this->addSql('CREATE TABLE stations (id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(150) NOT NULL, address VARCHAR(250) NOT NULL, schedule JSON NOT NULL, time DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tools (id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, station_id VARCHAR DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAFADE7721BDB235 ON tools (station_id)');
        $this->addSql('ALTER TABLE stations_clients ADD CONSTRAINT FK_9CDF64E819EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stations_clients ADD CONSTRAINT FK_9CDF64E821BDB235 FOREIGN KEY (station_id) REFERENCES stations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C30021BDB235 FOREIGN KEY (station_id) REFERENCES stations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_contracts ADD CONSTRAINT FK_CEE04662BC18698D FOREIGN KEY (employ_id) REFERENCES employees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tools ADD CONSTRAINT FK_EAFADE7721BDB235 FOREIGN KEY (station_id) REFERENCES stations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE stations_clients DROP CONSTRAINT FK_9CDF64E819EB6921');
        $this->addSql('ALTER TABLE stations_clients DROP CONSTRAINT FK_9CDF64E821BDB235');
        $this->addSql('ALTER TABLE employees DROP CONSTRAINT FK_BA82C30021BDB235');
        $this->addSql('ALTER TABLE job_contracts DROP CONSTRAINT FK_CEE04662BC18698D');
        $this->addSql('ALTER TABLE tools DROP CONSTRAINT FK_EAFADE7721BDB235');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE stations_clients');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE job_contracts');
        $this->addSql('DROP TABLE stations');
        $this->addSql('DROP TABLE tools');
    }
}
