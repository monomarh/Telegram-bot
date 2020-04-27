<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\BotService;
use BotMan\BotMan\BotMan;
use \DateTime;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /** @var Botman */
    private Botman $botMan;

    /**
     * @param BotService $botService
     */
    public function __construct(BotService $botService)
    {
        $this->botMan = $botService->getBot();
    }

    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        /** @var ManagerRegistry $entityManager */
        $entityManager = $this->getDoctrine();

        $this->botMan->hears('/all', static function (BotMan $bot) use ($entityManager) {
            /** @var UserRepository $userRepositry */
            $userRepository = $entityManager->getRepository(User::class);

            /** @var User $user */
            $user = $userRepository->findOneBy(['userId' => $bot->getUser()->getId()]);

            $dayToLive = (new DateTime('now'))->diff(new DateTime('2071-12-19'))->format('%a');

            if ($user) {
                $bot->reply(sprintf(
                    'Hello %s, your live in %s and you\'re %s years old. You have %s days to live',
                    $user->getName(),
                    $user->getLocation()->getFullAddress() ?? '?',
                    $user->getBirthday()
                        ? $user->getBirthday()->diff(new \DateTime())->format('%Y')
                        : '?',
                    $dayToLive
                ));
            } else {
                $bot->reply('Hello anonym.');
            }
        });

        $this->botMan->hears('/name {name}', static function (BotMan $bot, string $name) use ($entityManager) {
            /** @var User $user */
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneByTelegramUserId((int) $bot->getUser()->getId());

            if ($user) {
                $user->setName($name);

                $bot->reply(sprintf('You change name: Hello %s.', $user->getName()));
            } else {
                $newUser = new User();
                $newUser->setName($name);
                $newUser->setTelegramUserId((int)$bot->getUser()->getId());

                $bot->reply(sprintf('Hello %s.', $newUser->getName()));

                $entityManager->getManager()->persist($newUser);
            }

            $entityManager->getManager()->flush();
        });

        $this->botMan->hears('/city {city}', static function (BotMan $bot, string $city) use ($entityManager) {
            /** @var User $user */
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneByTelegramUserId((int) $bot->getUser()->getId());

            if ($user !== null) {
                if ($user->getLocation()->getCity()) {
                    $user->setCity($city);
                    $bot->reply(sprintf('You change city: you live in %s.', $user->getCity()));
                } else {
                    $user->setCity($city);
                    $bot->reply(sprintf('You live in %s.', $user->getCity()));
                }
            } else {
                $bot->reply('At first, please, setup "/name".');
                return;
            }

            $entityManager->getManager()->flush();
        });

        $this->botMan->hears('/country {country}', static function (BotMan $bot, string $country) use ($entityManager) {
            /** @var User $user */
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneByTelegramUserId((int) $bot->getUser()->getId());

            if ($user !== null) {
                if ($user->getCity()) {
                    $user->setCity($city);
                    $bot->reply(sprintf('You change city: you live in %s.', $user->getCity()));
                } else {
                    $user->setCity($city);
                    $bot->reply(sprintf('You live in %s.', $user->getCity()));
                }
            } else {
                $bot->reply('At first, please, send "/name \'YOUR NAME\'".');
                return;
            }

            $entityManager->getManager()->flush();
        });

        $this->botMan->hears('/born {birthday}', static function (BotMan $bot, string $birthday) use ($entityManager) {
            /** @var User $user */
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneByTelegramUserId((int) $bot->getUser()->getId());

            if ($user !== null) {
                $birthdayDate = new \DateTime($birthday);

                if ($user->getBirthday()) {
                    $user->setBirthday($birthdayDate);
                    $bot->reply(sprintf(
                        'You corrected birthday: you\'re %s years old.',
                        $user->getBirthday()->diff(new \DateTime())->format('%Y')
                    ));
                } else {
                    $user->setBirthday($birthdayDate);
                    $bot->reply(sprintf(
                        'You\'re %s years old.',
                        $user->getBirthday()->diff(new \DateTime())->format('%Y')
                    ));
                }
            } else {
                $bot->reply('At first, please, send "/name \'YOUR NAME\'"');
                return;
            }

            $entityManager->getManager()->flush();
        });

        $this->botMan->hears('/help', static function (BotMan $bot) {
            $commandList =
                '/help - list of commands' . PHP_EOL .
                '/all - sends all info' . PHP_EOL .
                '/name "NAME" - your name' . PHP_EOL .
                '/city "CITY" - your city for weather' . PHP_EOL .
                '/city "COUNTRY" - your country for weather' . PHP_EOL .
                '/born "BIRTHDAY" - your birthday in format dd.mm.yyyy' . PHP_EOL;
            $bot->reply($commandList);
        });

        $this->botMan->fallback(static function (BotMan $bot) {
            $bot->reply('Type /help for a list of commands');
        });

        $this->botMan->listen();

        return new Response('All good');
    }
}
