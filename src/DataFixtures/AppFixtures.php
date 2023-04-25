<?php

namespace App\DataFixtures;

use App\Entity\Bien;
use App\Entity\Contact;
use App\Entity\Standing;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;
use Symfony\Component\Validator\Constraints\NotNull;

class AppFixtures extends Fixture
{

    private Generator $faker;
  
    

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void


    {
       //user 
       $users =[];
       $admin = new User();
       $admin ->setFullName('Administrateur de Sylove')
              ->setLieu('Cotonou')
              ->setTelephone(67922379)
              ->setEmail('adminsylove@gmail.com')
              ->setRoles(['ROLE_USER','ROLE_ADMIN'])
              ->setPlainPassword('password');

       $users[] = $admin;  
       $manager->persist($admin);
       
        for ($i=0; $i < 5 ; $i++) {            
            $user = new User();
            $user ->setFullName($this->faker->word())
                  ->setLieu(mt_rand(0, 1) === 1 ? $this->faker->firstName(): null)
                  ->setEmail($this->faker->email())
                  ->setTelephone(61256235)
                  ->setRoles(['ROLE_USER'])
                  ->setPlainPassword('password');
                  
             $users[]= $user;
            $manager->persist($user);    
        }

       //biens
       $biens = [];
        for ($i=0; $i <15; $i++) { 
            $bien = new Bien();
            $bien ->setName($this->faker->word(20))
                  ->setCity($this->faker->word(20))
                  ->setSold(mt_rand(0, 1) == 1 ? true: false)
                  ->setPrice(mt_rand(1, 1000000))
                  ->setAdress($this->faker->word(20))
                  ->setBedrooms(mt_rand(1,50))
                  ->setDescription($this->faker->text(255))
                  ->setSurface(mt_rand(1, 100))
                  ->setFloor(mt_rand(1, 10))
                  ->setRooms(mt_rand(0, 50))
                  ->setHeat(mt_rand(0, 4))
                  ->setUser($users[mt_rand(0, count($users) - 1)]);         


        $biens[] = $bien;
         $manager->persist($bien);

        }

        //standing
        foreach ( $biens as $bien){
            for ($k=0; $k < mt_rand(0, 10) ; $k++){
                $standing = new Standing();
                $standing ->setName($this->faker->word())
                          ->setBien($bien);

               $manager->persist($standing);    
             }               
        }



        //contact

        for ($i=0; $i <5 ; $i++) { 
            $contact = new Contact();
            $contact ->setName($this->faker->word())
                    ->setEmail($this->faker->email())
                    ->setObjet('Demande n°' . ($i + 1))
                    ->setMessage($this->faker->text());

               $manager->persist($contact); 




        }

        $manager->flush();

    }

}
