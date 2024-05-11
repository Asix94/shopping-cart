<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510220441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Seller Table ';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('seller');
        $table->addColumn('id', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('name', 'string', ['notnull' => false]);
        $table->setPrimaryKey(['id']);

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('seller');
    }
}
