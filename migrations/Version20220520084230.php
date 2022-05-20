<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520084230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create bonus salary policy config';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE department_bonus_salary_policy_config (id VARCHAR(64) NOT NULL, bonus_salary_policy text NOT NULL, department_id VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_38D2FE68AE80F5DF ON department_bonus_salary_policy_config (department_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE department_bonus_salary_policy_config');
    }
}
