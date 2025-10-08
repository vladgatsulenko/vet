<?php

namespace App\DataFixtures;

use App\Entity\PharmacologicalGroup;
use App\Entity\AnimalSpecies;
use App\Entity\Product;
use App\Entity\ProductManual;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');

        $group1 = new PharmacologicalGroup();
        $group1->setName('Антибактериальные средства');
        $manager->persist($group1);

        $group2 = new PharmacologicalGroup();
        $group2->setName('Противовоспалительные средства');
        $manager->persist($group2);

        $dog = new AnimalSpecies();
        $dog->setName('Собаки');
        $manager->persist($dog);

        $cat = new AnimalSpecies();
        $cat->setName('Кошки');
        $manager->persist($cat);

        for ($i = 0; $i < 6; $i++) {
            $p = new Product();
            $p->setName($faker->word . ' ' . ($i + 1));
            $p->setPharmacologicalGroup(($i % 2 === 0) ? $group1 : $group2);
            $p->setAnimalSpecies(($i % 2 === 0) ? $dog : $cat);
            $p->setDescriptionShort($faker->sentence(3));
            $p->setDescriptionMedium($faker->text(200));
            $p->setDescriptionFull($faker->paragraph(4));
            $p->setIngredients('Состав: ' . $faker->words(5, true));
            $p->setPharmacologicalProperties('Фармакологические свойства: ' . $faker->sentence());
            $p->setIndicationsForUse('Показания: ' . $faker->sentence());
            $p->setDosageAndAdministration('Способ применения: ' . $faker->sentence());
            $p->setRestrictions('Ограничения: ' . $faker->sentence());
            $manager->persist($p);

            $manual = new ProductManual();
            $manual->setProduct($p);
            $manual->setText($faker->paragraphs(3, true));
            $manager->persist($manual);
        }

        $manager->flush();
    }
}
