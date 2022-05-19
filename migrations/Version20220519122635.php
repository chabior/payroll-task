<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220519122635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add payroll list table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('create table payroll_list
            (
                employee_id     varchar(64) not null
                    constraint payroll_list_pk
                        primary key,
                first_name      varchar     not null,
                last_name       varchar     not null,
                hired_at        timestamp   not null,
                department_name varchar     not null,
                bonus_salary    int default null,
                base_salary     int default null,
                total_salary    int default null,
                bonus_name      varchar(64) default null,
                updated_at      timestamp default null
            );');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE payroll_list');
    }
}
