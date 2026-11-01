<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\SendMailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:newsletter', description: 'Envoie la newsletter à tous les utilisateurs')]
class NewsletterCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private SendMailService $mailService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $envoyes = 0;

        foreach ($this->userRepository->findAll() as $user) {
            try {
                $this->mailService->send(SendMailService::FROM, $user->getEmail(), 'Notre newsletter', 'bonjour', [
                    'message' => 'Voici les nouvelles recettes de la semaine !',
                    'adresse' => $user->getEmail(),
                ]);
                ++$envoyes;
            } catch (\Throwable $e) {
                $io->error('Échec pour '.$user->getEmail().' : '.$e->getMessage());

                return Command::FAILURE;
            }
        }

        $io->success($envoyes.' email(s) de newsletter envoyé(s).');

        return Command::SUCCESS;
    }
}
