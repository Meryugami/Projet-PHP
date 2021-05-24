<?php

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

        

        /*
        $ex1= new Event();
        $ex1->SetName('Anselme');
        $ex1->setAddress('Nantes');
        $date=new DateTime();
        $date->setDate(2100,2,3);
        $ex1->setDateStart($faker->$date);
        $ex1->setDateEnd($faker->$date);
        $ex1->setUser($this->getReference('user_'.rand(0, 5)));
        $manager->persist($ex1);
		*/

		$manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
