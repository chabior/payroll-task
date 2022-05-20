<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220519133254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create department table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE department (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN department.id IS \'(DC2Type:uuid_type)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE department');
    }
}
