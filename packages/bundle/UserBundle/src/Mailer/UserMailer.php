<?php

declare(strict_types=1);

namespace Talav\UserBundle\Mailer;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Talav\Component\User\Model\UserInterface;
use Twig\Environment;

class UserMailer implements UserMailerInterface
{
    /** @var MailerInterface */
    protected $mailer;

    /** @var UrlGeneratorInterface */
    protected $router;

    /** @var Environment */
    protected $twig;

    /** @var array */
    protected $parameters;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, Environment $twig, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user): void
    {
//        $template = $this->parameters['template']['confirmation'];
//        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
//        $context = array(
//            'user' => $user,
//            'confirmationUrl' => $url,
//        );
//        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], (string) $user->getEmail());
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user): void
    {
        $template = '@TalavUser/email/reset.twig';
        $url = $this->router->generate('talav_user_reset_password', ['token' => $user->getPasswordResetToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $context = [
            'user' => $user,
            'confirmationUrl' => $url,
        ];
        $this->sendMessage($template, $context, $user->getEmail());
    }

    /**
     * {@inheritdoc}
     */
    public function sendRegistrationSuccessfulEmail(UserInterface $user): void
    {
        $template = '@TalavUser/email/welcome.twig';
        $context = [
            'user' => $user,
        ];
        $this->sendMessage($template, $context, $user->getEmail());
    }

    protected function sendMessage($templateName, $context, $toEmail)
    {
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = '';
        if ($template->hasBlock('body_html', $context)) {
            $htmlBody = $template->renderBlock('body_html', $context);
        }
        $message = (new Email())
            ->subject($subject)
            ->from(new Address($this->parameters['email'], $this->parameters['name']))
            ->to($toEmail);
        if (!empty($htmlBody)) {
            $message->html($htmlBody);
        }
        $message->text($textBody);
        $this->mailer->send($message);
    }
}
