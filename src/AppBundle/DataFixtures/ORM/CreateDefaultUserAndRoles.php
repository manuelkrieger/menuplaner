<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\UserRole;

class CreateDefaultUserAndRoles implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $adminRole = new UserRole('Administrator', UserRole::ROLE_ADMIN, new \DateTime());
        $manager->persist($adminRole);

        $userRole = new UserRole('User', UserRole::ROLE_USER, new \DateTime());
        $manager->persist($userRole);

        $this->addUser(
            'Administrator',
            'admin',
            'admin',
            $adminRole,
            $manager
        );
        $this->addUser(
            'User',
            'user',
            'user',
            $userRole,
            $manager
        );
        $manager->flush();
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param UserRole $role
     * @param ObjectManager $manager
     */
    private function addUser($name, $email, $password, UserRole $role, ObjectManager $manager)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);

        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $password);
        $user->setPassword($encoded);

        $user->setCreatedAt(new \DateTime());
        $user->addUserRole($role);
        $manager->persist($user);
    }
}
