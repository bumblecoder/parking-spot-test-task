<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626133612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parking_spot ADD vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE parking_spot ADD CONSTRAINT FK_C1A60A00545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C1A60A00545317D1 ON parking_spot (vehicle_id)');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486A31B2BA6');
        $this->addSql('DROP INDEX UNIQ_1B80E486A31B2BA6 ON vehicle');
        $this->addSql('ALTER TABLE vehicle DROP parking_spot_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `parking_spot` DROP FOREIGN KEY FK_C1A60A00545317D1');
        $this->addSql('DROP INDEX UNIQ_C1A60A00545317D1 ON `parking_spot`');
        $this->addSql('ALTER TABLE `parking_spot` DROP vehicle_id');
        $this->addSql('ALTER TABLE vehicle ADD parking_spot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486A31B2BA6 FOREIGN KEY (parking_spot_id) REFERENCES parking_spot (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B80E486A31B2BA6 ON vehicle (parking_spot_id)');
    }
}
