<?php

namespace Iritec\NewsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Iritec\NewsBundle\Entity\News;
use Faker\Factory;

/**
 * 
 */
class LoadNewsData implements FixtureInterface 
{
    public function load(ObjectManager $manager) 
    {
        $faker = Factory::create('ru_RU');
        
        for($i = 0; $i < 75; $i++) {
            $item = new News();
            
            $item->setDate($faker->dateTimeBetween('-2 years'));
            $item->setTitle($faker->realText(70));
            $item->setContent($faker->realText(200));
            
            $manager->persist($item);
        }
        
        $manager->flush();
    }
}
