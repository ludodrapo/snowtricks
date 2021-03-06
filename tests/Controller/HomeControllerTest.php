<?php

declare(strict_types=1);

namespace tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * class HomeControllerTest
 * @package tests\Controller
 */
class HomeControllerTest extends WebTestCase
{
    private $client = null;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @return void
     */
    public function testHomepageIsUp()
    {
        $this->client->request('GET', '/');
        static::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testHomepageDisplaysAtLeastOneTrick()
    {
        $this->client->request('GET', '/');
        $this->assertSelectorExists('.card-body');
    }

    /**
     * @return void
     */
    public function testHomepageDisplaysLoggedInUserScreenName()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/');
        $this->assertSelectorTextContains('h3', 'Bienvenue ' . $testUser->getScreenName() . ' !');
    }
}
