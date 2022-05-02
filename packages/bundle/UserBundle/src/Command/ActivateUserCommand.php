<?php

declare(strict_types=1);

namespace Talav\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Talav\Component\User\Manager\UserManagerInterface;

class ActivateUserCommand extends Command
{
    protected static $defaultName = 'talav:user:activate';

    public function __construct(
        protected UserManagerInterface $userManager
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('talav:user:activate')
            ->setDescription('Activate a user')
            ->setDefinition([new InputArgument('username', InputArgument::REQUIRED, 'The username')])
            ->setHelp(
                <<<'EOT'
The <info>talav:user:activate</info> command activates a user (so they will be able to log in):
  <info>php %command.full_name% matthieu</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $this->userManager->activate($username);
        $output->writeln(sprintf('User "%s" has been activated.', $username));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('username', $answer);
        }
    }
}
