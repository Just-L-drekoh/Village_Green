<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Rubric;
use App\Entity\Address;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\SupplierDetails;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $faker = Factory::create('fr_FR');

        //service

        $dataServiceType=['equipe','apres-vente','commercial'];
        for ($i = 0; $i < 10; $i++) {

            $service = new Service();
            $service->setServType($faker->randomElement($dataServiceType));
            $manager->persist($service);
        }
        $manager->flush();

    

        // User
        $serviceRepository = $manager->getRepository(Service::class);
        $services = $serviceRepository->findAll();

        //Administrateur

        $user = new User();
        $user->setEmail('Village-green@mail.fr');
        $user->setPassword(password_hash('admin', PASSWORD_DEFAULT));
        $user->setUserFirstname('admin');
        $user->setUserLastname('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUserPhone($faker->phoneNumber()); 
        $user->setUserRef('Cli:'.mt_rand(999, 99999));
        $user->setCoef($faker->randomFloat(2, 0, 100));
        $user->setUserLastConn(new \DateTimeImmutable());
        $user->setVerified(false);
        $user->setService($services[array_rand($services)]);
        $manager->persist($user);
        $manager->flush();

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
            $user->setService($services[array_rand($services)]);
            $manager->persist($user);
        }
        $manager->flush();
        // Adresse
        
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();
        
        for ($i = 0; $i < 10; $i++) 
        {
            $address = new Address();
            $address->setAdrAddress($faker->address());
            $address->setUser($users[array_rand($users)]);
            $address->setAdrCity($faker->city());
            $address->setAdrCp($faker->postcode());
            $address->setAdrType($faker->randomElement(['livraison', 'facturation']));
            $address->setAdrCpl($faker->randomElement(['A', 'B', 'C']));
            $manager->persist($address);
        }

        $manager->flush();

        //Fournisseur Detail
       

        $dataSplType=['importateur','constructeur'];

        for ($i = 0; $i < 10; $i++) 
        {
            $supplier = new SupplierDetails();
            $supplier->setSplType($faker->randomElement($dataSplType));
            $supplier->setSplStatus(true);
            $supplier->setUser($users[array_rand($users)]);
            $manager->persist($supplier);

        }
        $manager->flush();
        $supplierRepository = $manager->getRepository(SupplierDetails::class);
        $suppliers = $supplierRepository->findAll();

         // Créer les rubriques parentes

      
         for ($i = 0; $i < 6; $i++) {
             $rubric = new Rubric();
             $rubric->setRubLabel($faker->word());
             $rubric->setRubSlug($faker->slug());
             $rubric->setRubDesc($faker->text());
             $rubric->setRubImg($faker->imageUrl());
         
         
             $manager->persist($rubric);
         }
         $manager->flush();

         //sous rubriques
         $rubricRepository = $manager->getRepository(Rubric::class);
         $rubrics = $rubricRepository->findAll();
         
         for ($i = 0; $i < 6; $i++) {
             $rubric = new Rubric();
             $rubric->setRubLabel($faker->word());
             $rubric->setRubSlug($faker->slug());
             $rubric->setRubDesc($faker->text());
             $rubric->setRubImg($faker->imageUrl());
             $rubric->setParent($rubrics[array_rand($rubrics)]);
             $manager->persist($rubric);
         }
         $manager->flush();
    


        //Produits (instruments)

        $dataProdType=['guitare','violon','flutes'];
        for ($i = 0; $i < 10; $i++) 
        {
            $product = new Product();
            $product->setProdLabel($faker->randomElement($dataProdType));
            $product->setProdSlug($faker->slug());
            $product->setProdRef('REF'.mt_rand(999, 99999));
            $product->setProdDesc($faker->text());
            $product->setProdPrice($faker->randomFloat(2, 0, 100));
            $product->setProdStock(mt_rand(0, 100));
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(null);
            $product->setRubric($rubrics[array_rand($rubrics)]);
            $product->setSupplier($suppliers[array_rand($suppliers)]);
            $manager->persist($product);
        }

        $manager->flush();

        $productRepository = $manager->getRepository(Product::class);
        $products = $productRepository->findAll();

        //images

        for ($i = 0; $i < 10; $i++) {
            $image = new Image();
            $image->setProdImg($faker->imageUrl());
            $image->setProduct($products[array_rand($products)]);
            $manager->persist($image);
        }
        $manager->flush();
    }
}
