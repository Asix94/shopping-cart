<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512081428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Cart table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('cart');
        $table->addColumn('id', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('confirmed', Types::BOOLEAN)->setNotnull(true);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('cart');
    }
}
