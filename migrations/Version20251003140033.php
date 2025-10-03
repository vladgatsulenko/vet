<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251003140033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal_species (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacological_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, pharmacological_group_id INT NOT NULL, animal_species_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description_short VARCHAR(255) DEFAULT NULL, description_medium LONGTEXT DEFAULT NULL, description_full LONGTEXT DEFAULT NULL, ingredients LONGTEXT DEFAULT NULL, pharmacological_properties LONGTEXT DEFAULT NULL, indications_for_use LONGTEXT DEFAULT NULL, dosage_and_administration LONGTEXT DEFAULT NULL, restrictions LONGTEXT DEFAULT NULL, INDEX IDX_D34A04ADDCA46204 (pharmacological_group_id), INDEX IDX_D34A04AD6F540084 (animal_species_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_manual (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, text MEDIUMTEXT, INDEX IDX_72A7FF604584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDCA46204 FOREIGN KEY (pharmacological_group_id) REFERENCES pharmacological_group (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD6F540084 FOREIGN KEY (animal_species_id) REFERENCES animal_species (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE product_manual ADD CONSTRAINT FK_72A7FF604584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADDCA46204');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD6F540084');
        $this->addSql('ALTER TABLE product_manual DROP FOREIGN KEY FK_72A7FF604584665A');
        $this->addSql('DROP TABLE animal_species');
        $this->addSql('DROP TABLE pharmacological_group');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_manual');
    }
}
