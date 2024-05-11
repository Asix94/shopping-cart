<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511114718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Product Table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('product');
        $table->addColumn('id', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('sellerId', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('name', Types::STRING)->setNotnull(true);
        $table->addColumn('price', Types::FLOAT)->setNotnull(true);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('seller', ['sellerId'], ['id'], ['onDelete' => 'CASCADE']);

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('product');
    }
}
