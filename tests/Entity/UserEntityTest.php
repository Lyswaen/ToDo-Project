<?php


namespace App\Tests\Entity;


use App\Entity\User;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEntityTest extends KernelTestCase
{

    use FixturesTrait;

    public function testValidEntity()
    {
        self::bootKernel();
        $passwordEncoder = self::$container->get(UserPasswordEncoderInterface::class);
        $user = (new User())
            ->setUsername('Tester')
            ->setEmail('test@gmail.com')
            ->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($passwordEncoder->encodePassword($user, '1234'));
        $errors = self::$container->get('validator')->validate($user);
        $this->assertCount(0, $errors);
    }

    public function testGetters() {
        self::bootKernel();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml'
        ]);
        $userRepository = self::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $this->assertIsNumeric($user->getId());
        $this->assertIsString($user->getUsername());
        $this->assertIsArray($user->getRoles());
        $this->assertIsString($user->getPassword());
        $this->assertIsString($user->getEmail());
    }
}