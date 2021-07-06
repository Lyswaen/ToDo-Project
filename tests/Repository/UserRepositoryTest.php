<?php


namespace App\Tests\Repository;


use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\Repository\UserRepository
 */
class UserRepositoryTest extends KernelTestCase
{

    use FixturesTrait;

    public function testUpgradePassword()
    {
        self::bootKernel();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml'
        ]);

        $userRepository = self::$container->get(UserRepository::class);
        $passwordEncoder = self::$container->get(UserPasswordEncoderInterface::class);
        $user = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);
        $userRepository->upgradePassword($user, $passwordEncoder->encodePassword($user, '1234'));

        $this->assertTrue($passwordEncoder->isPasswordValid($user, '1234'));
    }

    public function testException() {
        self:self::bootKernel();

        $userRepository = self::$container->get(UserRepository::class);
        $passwordEncoder = self::$container->get(UserPasswordEncoderInterface::class);

        $shiro = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);
        $password = $passwordEncoder->encodePassword($shiro, '1234');

        $user = new FakeUser();

        $this->expectException(UnsupportedUserException::class);

        $userRepository->upgradePassword($user, $password);
    }
}

class FakeUser implements UserInterface {

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}