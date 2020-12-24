<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20201224174852 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX geo_zone_type_idx ON geo_zone (type)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX geo_zone_type_idx ON geo_zone');
    }
}
