<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@crm.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'azerty'));
        $manager->persist($user);

        for($i = 0; $i < 500;$i++)
        {
            $customer = new Customer();
            $customer->setFirstname('Client ' . $i);
            $customer->setLastname('Doe');
            $customer->setEmail('client_' . $i . '@customer-crm.fr');
            $customer->setCreatedBy($user);

            $manager->persist($customer);
        }

        for($i = 0; $i < 100; $i++)
        {
            $company = (new Company())
            ->setName('Entreprise ' . $i)
            ->setSiret($this->randomizeSiret());

            $manager->persist($company);
        }

        $manager->flush();
    }

    /**
     * @return string
     */
    private function randomizeSiret() : string
    {
        $siret = '';
        for($i=0;$i<14;$i++)
        {
            $siret .= random_int(1,9);
        }
        return $siret;
    }
}
