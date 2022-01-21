<?php

namespace App\Controller\Front;


use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class MessageController extends AbstractController
{
    public function contact()
    {
        return $this->render('front/contact.html.twig');
    }

    public function message(Request $request, MailerInterface $mailerInterface)
    {
        $message =  $request->request->get('message');

        $email = (new TemplatedEmail())

            ->from('dd@test.com')
            ->to('gg@test.com')
            ->subject('message')
            ->htmlTemplate('front/email.html.twig')
            ->context(['message' => $message]);

        $mailerInterface->send($email);

        return $this->redirectToRoute('contact');
    }
    
    
}
