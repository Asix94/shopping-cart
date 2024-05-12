<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512131915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create cart item table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('cart_item');
        $table->addColumn('cart_id', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('product_id', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('quantity', Types::INTEGER)->setNotnull(true);
        $table->setPrimaryKey(['cart_id', 'product_id']);
        $table->addIndex(['cart_id']);
        $table->addForeignKeyConstraint('cart', ['cart_id'], ['id'],["onUpdate" => "CASCADE", "onDelete" => "CASCADE"]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('cart_item');
    }
}
