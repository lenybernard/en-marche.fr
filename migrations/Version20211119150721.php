<?php

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211119150721 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE pap_closure_history (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          action VARCHAR(10) NOT NULL,
          campaign_id INT UNSIGNED NOT NULL,
          building_id INT UNSIGNED DEFAULT NULL,
          building_block_id INT UNSIGNED DEFAULT NULL,
          floor_id INT UNSIGNED DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by_id INT UNSIGNED DEFAULT NULL,
          INDEX IDX_5A893B94F639F774 (campaign_id),
          INDEX IDX_5A893B9432618357 (building_block_id),
          INDEX IDX_5A893B94854679E2 (floor_id),
          INDEX IDX_5A893B94B03A8386 (created_by_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          pap_closure_history
        ADD
          CONSTRAINT FK_5A893B94F639F774 FOREIGN KEY (campaign_id) REFERENCES pap_campaign (id)');
        $this->addSql('ALTER TABLE
          pap_closure_history
        ADD
          CONSTRAINT FK_5A893B944D2A7E12 FOREIGN KEY (building_id) REFERENCES pap_building (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5A893B944D2A7E12 ON pap_closure_history (building_id)');
        $this->addSql('ALTER TABLE
          pap_closure_history
        ADD
          CONSTRAINT FK_5A893B9432618357 FOREIGN KEY (building_block_id) REFERENCES pap_building_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          pap_closure_history
        ADD
          CONSTRAINT FK_5A893B94854679E2 FOREIGN KEY (floor_id) REFERENCES pap_floor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          pap_closure_history
        ADD
          CONSTRAINT FK_5A893B94B03A8386 FOREIGN KEY (created_by_id) REFERENCES adherents (id) ON DELETE
        SET
          NULL');
        $this->addSql('ALTER TABLE
          pap_bulding_block_statistics
        ADD
          closed_by_id INT UNSIGNED DEFAULT NULL,
        ADD
          closed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE
          pap_bulding_block_statistics
        ADD
          CONSTRAINT FK_DB09D487E1FA7797 FOREIGN KEY (closed_by_id) REFERENCES adherents (id) ON DELETE
        SET
          NULL');
        $this->addSql('CREATE INDEX IDX_DB09D487E1FA7797 ON pap_bulding_block_statistics (closed_by_id)');
        $this->addSql('ALTER TABLE
          pap_bulding_statistics
        ADD
          closed_by_id INT UNSIGNED DEFAULT NULL,
        ADD
          closed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE
          pap_bulding_statistics
        ADD
          CONSTRAINT FK_2984AD93E1FA7797 FOREIGN KEY (closed_by_id) REFERENCES adherents (id) ON DELETE
        SET
          NULL');
        $this->addSql('CREATE INDEX IDX_2984AD93E1FA7797 ON pap_bulding_statistics (closed_by_id)');
        $this->addSql('ALTER TABLE
          pap_floor_statistics
        ADD
          closed_by_id INT UNSIGNED DEFAULT NULL,
        ADD
          closed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE
          pap_floor_statistics
        ADD
          CONSTRAINT FK_853B68C8E1FA7797 FOREIGN KEY (closed_by_id) REFERENCES adherents (id) ON DELETE
        SET
          NULL');
        $this->addSql('CREATE INDEX IDX_853B68C8E1FA7797 ON pap_floor_statistics (closed_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE pap_closure_history');
        $this->addSql('ALTER TABLE pap_bulding_block_statistics DROP FOREIGN KEY FK_DB09D487E1FA7797');
        $this->addSql('DROP INDEX IDX_DB09D487E1FA7797 ON pap_bulding_block_statistics');
        $this->addSql('ALTER TABLE pap_bulding_block_statistics DROP closed_by_id, DROP closed_at');
        $this->addSql('ALTER TABLE pap_bulding_statistics DROP FOREIGN KEY FK_2984AD93E1FA7797');
        $this->addSql('DROP INDEX IDX_2984AD93E1FA7797 ON pap_bulding_statistics');
        $this->addSql('ALTER TABLE pap_bulding_statistics DROP closed_by_id, DROP closed_at');
        $this->addSql('ALTER TABLE pap_floor_statistics DROP FOREIGN KEY FK_853B68C8E1FA7797');
        $this->addSql('DROP INDEX IDX_853B68C8E1FA7797 ON pap_floor_statistics');
        $this->addSql('ALTER TABLE pap_floor_statistics DROP closed_by_id, DROP closed_at');
    }
}
