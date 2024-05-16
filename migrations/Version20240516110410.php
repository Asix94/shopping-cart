<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516110410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create default data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'INSERT INTO seller (id, name) 
                 VALUES("68f0ff2d-f49b-44ea-980c-35036818af12","test") ');
        $this->addSql(
            'INSERT INTO product (id, seller_id, name, price) 
                 VALUES ("b7783f83-0eab-4bc1-8fb0-6f975c5f9a7d","68f0ff2d-f49b-44ea-980c-35036818af12", "test", 20),
                        ("20c9feec-f5f2-4b1f-8fd6-041f520dfaa1","68f0ff2d-f49b-44ea-980c-35036818af12", "test", 20)'
        );
        $this->addSql(
            'INSERT INTO cart (id, confirmed) 
                 VALUES ("8542b225-53f6-441e-b909-9bf6c3604fc9", 0)'
        );
        $this->addSql(
            'INSERT INTO cart_item (cart_id, product_id, quantity) 
                 VALUES ("8542b225-53f6-441e-b909-9bf6c3604fc9","b7783f83-0eab-4bc1-8fb0-6f975c5f9a7d", 1),
                        ("8542b225-53f6-441e-b909-9bf6c3604fc9","20c9feec-f5f2-4b1f-8fd6-041f520dfaa1", 1)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE cart_item');
        $this->addSql('TRUNCATE cart');
        $this->addSql('TRUNCATE product');
        $this->addSql('TRUNCATE seller');
    }
}
