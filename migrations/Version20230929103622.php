<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929103622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6DD1AB22F');
        $this->addSql('DROP INDEX IDX_1E512EC6DD1AB22F ON reponses');
        $this->addSql('ALTER TABLE reponses DROP the_answer');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponses ADD the_answer INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6DD1AB22F FOREIGN KEY (the_answer) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_1E512EC6DD1AB22F ON reponses (the_answer)');
    }
}
