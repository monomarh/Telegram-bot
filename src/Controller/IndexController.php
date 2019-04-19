<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use BotMan\BotMan\BotMan;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use BotMan\Drivers\Telegram\TelegramDriver;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        $config = [
             'telegram' => [
                'token' => $_ENV['SECRET_TOKEN']
             ]
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        $botman = BotManFactory::create($config);

        /** @var ManagerRegistry $entityManager */
        $entityManager = $this->getDoctrine();

        $botman->hears('hello', static function(BotMan $bot) {
            $bot->reply('Hello yourself.');
        });

        $botman->hears('call me {name}', static function(BotMan $bot, string $name) use ($entityManager) {
            /** @var UserRepository $userRepositry */
            $userRepository = $entityManager->getRepository(User::class);

            /** @var User $user */
            $user = $userRepository->findOneBy(['userId' => $bot->getUser()->getId()]);

            if ($user !== null) {
                $user->setName($name);

                $bot->reply(sprintf('You change name: Hello %s.', $user->getName()));

                $entityManager->getManager()->persist($user);
            } else {
                $newUser = new User();
                $newUser->setName($name)->setUserId((int)$bot->getUser()->getId());

                $bot->reply(sprintf('Hello %s.', $newUser->getName()));

                $entityManager->getManager()->persist($newUser);
            }

            $entityManager->getManager()->flush();
        });

        $botman->hears('i live in {city}', static function(BotMan $bot, string $city) {
            $bot->reply(sprintf('Weather in %s as ass.', $city));
        });

        $botman->hears('i was born {birthday}', static function(BotMan $bot, string $birthday) {
            $birthdayDate = new \DateTime($birthday);
            $bot->reply(sprintf('You\'re %s years old.', $birthdayDate->diff(new \DateTime())->format('%Y')));
        });

        $botman->hears('help', static function(BotMan $bot) {
            $commandList =
                'help - command list with description' . PHP_EOL .
                'call me "YOUR NAME" - enter your name instead of "YOUR NAME", after that the bot will call you in a new way' . PHP_EOL .
                'i live in "YOUR CITY" - enter your city instead of "YOUR CITY", after that the bot will send the weather for this city' . PHP_EOL;
            $bot->reply(sprintf('You\'re %s years old.', $commandList));
        });

        $botman->hears('dump', static function(BotMan $bot) use ($entityManager)  {
            $users = $entityManager->getRepository(User::class)->findAll();
            $userNames = [];
            foreach ($users as $user) {
                $userNames[] = $user->getName();
            }
            $bot->reply(sprintf('You\'re %s years old.', implode(',', $userNames)));
        });

        $botman->fallback(static function(BotMan $bot) {
            $bot->reply('Sorry, I did not understand these commands. Type help for command list');
        });

        $botman->listen();

        return new Response('All good');
    }
}
