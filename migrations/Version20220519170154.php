<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220519170154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (employee_id VARCHAR(64) NOT NULL, base_salary INT NOT NULL, bonus_salary INT NOT NULL, hired_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, department_id VARCHAR(64) NOT NULL, PRIMARY KEY(employee_id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE employee');
    }
}
