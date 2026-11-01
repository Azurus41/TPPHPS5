<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    public const FROM = 'no-reply@monsite.net';
    public const ADMIN = 'admin@a.com';

    public function __construct(
        private MailerInterface $mailer,
        private UserRepository $userRepository,
    ) {
    }

    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }

    public function dire_bonjour_a_tous(): int
    {
        $nb = 0;
        foreach ($this->userRepository->findAll() as $user) {
            $this->send(self::FROM, $user->getEmail(), 'Bonjour', 'bonjour', [
                'message' => 'Bonjour !',
                'adresse' => $user->getEmail(),
            ]);
            ++$nb;
        }

        return $nb;
    }

    public function dire_bonjour(string $email): void
    {
        $this->send(self::FROM, $email, 'Connexion détectée', 'bonjour', [
            'message' => 'Bonjour, une connexion a été détectée.',
            'adresse' => $email,
        ]);
    }
}
