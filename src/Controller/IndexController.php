<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use BotMan\BotMan\BotMan;
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

        $botman->hears('hello', static function(BotMan $bot) use ($entityManager) {
            /** @var UserRepository $userRepositry */
            $userRepository = $entityManager->getRepository(User::class);

            /** @var User $user */
            $user = $userRepository->findOneBy(['userId' => $bot->getUser()->getId()]);

            if ($user) {
                $bot->reply(sprintf(
                    'Hello %s, your live in %s and you\'re %s years old.',
                    $user->getName(),
                    $user->getCity() ?? '',
                    $user->getBirthday()->format('%Y') ?? ''
                ));
            } else {
                $bot->reply('Hello anonym.');
            }
        });

        $botman->hears('call me {name}', static function(BotMan $bot, string $name) use ($entityManager) {
            /** @var UserRepository $userRepositry */
            $userRepository = $entityManager->getRepository(User::class);

            /** @var User $user */
            $user = $userRepository->findOneBy(['userId' => $bot->getUser()->getId()]);

            if ($user) {
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

        $botman->hears('i live in {city}', static function(BotMan $bot, string $city) use ($entityManager) {
            /** @var UserRepository $userRepositry */
            $userRepository = $entityManager->getRepository(User::class);

            /** @var User $user */
            $user = $userRepository->findOneBy(['userId' => $bot->getUser()->getId()]);

            if ($user !== null) {
                if ($user->getCity()) {
                    $user->setCity($city);
                    $bot->reply(sprintf('You change city: you live in %s.', $user->getCity()));
                } else {
                    $user->setCity($city);
                    $bot->reply(sprintf('You live in %s.', $user->getCity()));
                }

                $entityManager->getManager()->persist($user);
            } else {
                $bot->reply('At first, please, send "call me \'YOUR NAME\'".');
                return;
            }

            $entityManager->getManager()->flush();

            $bot->reply(sprintf('Weather in %s as ass.', $user->getCity()));
        });

        $botman->hears('i was born {birthday}', static function(BotMan $bot, string $birthday)
        use ($entityManager) {
            /** @var UserRepository $userRepositry */
            $userRepository = $entityManager->getRepository(User::class);

            /** @var User $user */
            $user = $userRepository->findOneBy(['userId' => $bot->getUser()->getId()]);

            if ($user !== null) {
                $birthdayDate = new \DateTime($birthday);

                if ($user->getBirthday()) {
                    $user->setBirthday($birthdayDate);
                    $bot->reply(sprintf(
                        'You corrected birthday: you\'re %s years old.',
                        $user->getBirthday()->format('%Y')
                    ));
                } else {
                    $user->setBirthday($birthdayDate);
                    $bot->reply(sprintf('You\'re %s years old.', $user->getBirthday()->format('%Y')));
                }

                $entityManager->getManager()->persist($user);
            } else {
                $bot->reply('At first, please, send "call me \'YOUR NAME\'"');
                return;
            }

            $entityManager->getManager()->flush();
        });

        $botman->hears('help', static function(BotMan $bot) {
            $commandList =
                'help - ' . PHP_EOL . 'command list with description' . PHP_EOL . PHP_EOL .
                'call me "YOUR NAME" - ' . PHP_EOL . 'enter your name instead of "YOUR NAME", after that the bot will call you in a new way' . PHP_EOL . PHP_EOL .
                'i live in "YOUR CITY" - ' . PHP_EOL . 'enter your city instead of "YOUR CITY", after that the bot will send the weather for this city' . PHP_EOL . PHP_EOL .
                'i was born "YOUR BIRTHDAY" - ' . PHP_EOL . 'enter your birthday instead of "YOUR BIRTHDAY", after that the bot will send the remaining days in a new way' . PHP_EOL . PHP_EOL;
            $bot->reply($commandList);
        });

        $botman->fallback(static function(BotMan $bot) {
            $bot->reply('Sorry, I did not understand these commands. Type help for command list');
        });

        $botman->listen();

        return new Response('All good');
    }
}
