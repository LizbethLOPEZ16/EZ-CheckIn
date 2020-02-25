<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Vendors\TwilioMessaging;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * @Route("/messages")
 */
class SendMessageController extends AbstractController
{
    /**
     * @Route("/message", name="api_messages_whatsapp", methods={"POST"})
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendTwilioMessage(Request $request)
    {
        try {
            $tm = new TwilioMessaging();

            $data = json_decode(
                $request->getContent(),
                true
            );

            if (strtolower($data['type']) === 'whatsapp') {
                $message = $tm->sendWhatsAppMessage($data['phone'], $data['datetime'], $data['schedule']);
            } else if (strtolower($data['type']) === 'sms') {
                $message = $tm->sendSMSMessage($data['phone'], $data['datetime'], $data['schedule']);
            } else {
                throw new Exception("Type is missing and it is required!");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return new JsonResponse(['success' => 'success', 'message' => 'Message sent successfully!'], 200);
    }
}
