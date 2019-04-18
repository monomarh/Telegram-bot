<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TelegramBot\Api\Client;

/**
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    private $siteUrl;

    /**
     * @var string
     */
    private const SECRET_TOKEN = '818997148:AAGFFXJdbgkDX_Rms8eAR0xNygSFoEMRf10';

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $this->siteUrl = 'https://' . $request->getHttpHost();

//        try {
            $bot = new Client(self::SECRET_TOKEN);

            dump($this->siteUrl);

            $bot->setWebhook($this->siteUrl);

            $bot->command('start', static function ($message) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), 'Hello');
            });

            $bot->run();
//        } catch (\Exception $e) {
//            return new Response($e->getMessage());
//        }

        return new Response($this->siteUrl);
    }
}
