<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $password_encoder)
    {
        $this->password_encoder = $password_encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        foreach($this->getUserData() as [$name, $last_name, $email, $password, $api_key, $roles]){
            $user = new User();
            $user->setName($name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPassword($this->password_encoder->encodePassword($user, $password));
            $user->setVimeoApiKey($api_key);
            $user->setRoles($roles);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['Nico', 'Michelson', 'nico@nico.com', 'nico', 'nicomichelson', ['ROLE_ADMIN']],
            ['Roci', 'Cochinota', 'roci@roci.com', 'roci', null, ['ROLE_USER']]
        ];
    }
}
