<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116172111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__excercise AS SELECT id, name, weight, repeats, series, breake_time, min, max, training_id FROM excercise');
        $this->addSql('DROP TABLE excercise');
        $this->addSql('CREATE TABLE excercise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, weight DOUBLE PRECISION NOT NULL, repeats INTEGER NOT NULL, series INTEGER NOT NULL, min INTEGER NOT NULL, max INTEGER NOT NULL, training_id INTEGER NOT NULL, break_time INTEGER NOT NULL)');
        $this->addSql('INSERT INTO excercise (id, name, weight, repeats, series, break_time, min, max, training_id) SELECT id, name, weight, repeats, series, breake_time, min, max, training_id FROM __temp__excercise');
        $this->addSql('DROP TABLE __temp__excercise');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__excercise AS SELECT id, name, weight, repeats, series, break_time, min, max, training_id FROM excercise');
        $this->addSql('DROP TABLE excercise');
        $this->addSql('CREATE TABLE excercise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, repeats INTEGER NOT NULL, series INTEGER NOT NULL, min INTEGER NOT NULL, max INTEGER NOT NULL, training_id INTEGER NOT NULL, breake_time INTEGER NOT NULL)');
        $this->addSql('INSERT INTO excercise (id, name, weight, repeats, series, breake_time, min, max, training_id) SELECT id, name, weight, repeats, series, break_time, min, max, training_id FROM __temp__excercise');
        $this->addSql('DROP TABLE __temp__excercise');
    }
}
