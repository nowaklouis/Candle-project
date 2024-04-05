<?php

namespace App\Command;

use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Service\ContactService;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;

#[AsCommand(
    name: 'SendContactCommand',
    description: 'Envoi par email depuis la BDD',
)]
class SendContactCommand extends Command
{
    private $contactRepository;
    private $mailer;
    private $contactService;
    private $userRepository;

    public function __construct(
        ContactRepository $contactRepository,
        MailerInterface $mailer,
        ContactService $contactService,
        UserRepository $userRepository
    ) {
        $this->contactRepository = $contactRepository;
        $this->mailer = $mailer;
        $this->contactService = $contactService;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $toSend = $this->contactRepository->findBy(['isSend' => false]);
        $adress = new Address($this->userRepository->getUser()->getEmail(), $this->userRepository->getUser()->getUsername() . 'voir la suite');

        foreach ($toSend as $mail) {
            $email = (new Email())
                ->from($mail->getEmail())
                ->to($adress)
                ->subject('Nouveau message de ' . $mail->getName())
                ->text($mail->getMessage());

            $this->mailer->send($email);
            $this->contactService->isSend($mail);
        }

        return Command::SUCCESS;
    }
}
