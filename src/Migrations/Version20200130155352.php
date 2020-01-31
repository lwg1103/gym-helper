<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200130155352 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE excercise_instance ADD COLUMN base_excercise_id INTEGER NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__excercise_instance AS SELECT id, name, weight, repeats, break_time, result, training_instance_id FROM excercise_instance');
        $this->addSql('DROP TABLE excercise_instance');
        $this->addSql('CREATE TABLE excercise_instance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, weight INTEGER NOT NULL, repeats INTEGER NOT NULL, break_time INTEGER NOT NULL, result INTEGER NOT NULL, training_instance_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO excercise_instance (id, name, weight, repeats, break_time, result, training_instance_id) SELECT id, name, weight, repeats, break_time, result, training_instance_id FROM __temp__excercise_instance');
        $this->addSql('DROP TABLE __temp__excercise_instance');
    }
}
