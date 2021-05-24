<?php
    //Génération des fixtures pour les évènements
namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 15; $i++) {
            $event = new Event();
            $event->setName($faker->name);
            $event->setAddress($faker->address);
            $event->setDateStart($faker->dateTime);
            $event->setDateEnd($faker->dateTime);
            $event->setUser($this->getReference('user_'.rand(0, 5)));
            $this->addReference('event_'.$i, $event);
            $manager->persist($event);
        }


		$manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
