<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200203152114 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__excercise_instance AS SELECT id, name, weight, repeats, break_time, result, training_instance_id, base_excercise_id FROM excercise_instance');
        $this->addSql('DROP TABLE excercise_instance');
        $this->addSql('CREATE TABLE excercise_instance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, weight INTEGER NOT NULL, repeats INTEGER NOT NULL, break_time INTEGER NOT NULL, result INTEGER NOT NULL, training_instance_id INTEGER NOT NULL, base_excercise_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO excercise_instance (id, name, weight, repeats, break_time, result, training_instance_id, base_excercise_id) SELECT id, name, weight, repeats, break_time, result, training_instance_id, base_excercise_id FROM __temp__excercise_instance');
        $this->addSql('DROP TABLE __temp__excercise_instance');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__excercise_instance AS SELECT id, name, weight, repeats, break_time, result, training_instance_id, base_excercise_id FROM excercise_instance');
        $this->addSql('DROP TABLE excercise_instance');
        $this->addSql('CREATE TABLE excercise_instance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, weight INTEGER NOT NULL, repeats INTEGER NOT NULL, break_time INTEGER NOT NULL, result INTEGER NOT NULL, training_instance_id INTEGER NOT NULL, base_excercise_id INTEGER DEFAULT 0 NOT NULL)');
        $this->addSql('INSERT INTO excercise_instance (id, name, weight, repeats, break_time, result, training_instance_id, base_excercise_id) SELECT id, name, weight, repeats, break_time, result, training_instance_id, base_excercise_id FROM __temp__excercise_instance');
        $this->addSql('DROP TABLE __temp__excercise_instance');
    }
}
