<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Address;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $faker = Factory::create('fr_FR');

        //hashed password

    

        // User
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword(password_hash($faker->password(), PASSWORD_DEFAULT));
            $user->setUserFirstname($faker->Firstname());
            $user->setUserLastname($faker->Lastname());
            $user->setRoles(['ROLE_USER']);
            $user->setUserPhone($faker->phoneNumber()); 
            $user->setUserRef('Cli:'.mt_rand(999, 99999));
            $user->setCoef($faker->randomFloat(2, 0, 100));
            $user->setUserLastConn(new \DateTimeImmutable());
            $user->setVerified(false);
            $manager->persist($user);
        }
        // Adresse
        
        for ($i = 0; $i < 10; $i++) {
            $address = new Address();
            $address->setAdrAddress($faker->address());
            $address->setUser($user);
            $address->setAdrCity($faker->city());
            $address->setAdrCp($faker->postcode());
            $address->setAdrType($faker->randomElement(['livraison', 'facturation']));
            $address->setAdrCpl($faker->randomElement(['A', 'B', 'C']));
            $manager->persist($address);
        }
       

        $manager->persist($address);
        $manager->flush(); 

        

    }
}
