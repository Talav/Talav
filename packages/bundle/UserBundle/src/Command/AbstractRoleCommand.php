<?php

declare(strict_types=1);

namespace Talav\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Talav\Component\User\Manager\UserManagerInterface;

abstract class AbstractRoleCommand extends Command
{
    public function __construct(
        protected UserManagerInterface $userManager
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('role', InputArgument::OPTIONAL, 'The role'),
                new InputOption(
                    'super',
                    null,
                    InputOption::VALUE_NONE,
                    'Instead specifying role, use this to quickly add the super administrator role'
                ),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');
        $super = (true === $input->getOption('super'));
        if (null !== $role && $super) {
            throw new \InvalidArgumentException('You can pass either the role or the --super option (but not both simultaneously).');
        }
        if (null === $role && !$super) {
            throw new \RuntimeException('Not enough arguments.');
        }
        $this->executeRoleCommand($this->userManager, $output, $username, $super, $role);
    }

    /**
     * @see Command
     *
     * @param string $username
     * @param bool   $super
     * @param string $role
     */
    abstract protected function executeRoleCommand(
        UserManagerInterface $userManager,
        OutputInterface $output,
        $username,
        $super,
        $role
    );

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];
        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }
        if ((true !== $input->getOption('super')) && !$input->getArgument('role')) {
            $question = new Question('Please choose a role:');
            $question->setValidator(function ($role) {
                if (empty($role)) {
                    throw new \Exception('Role can not be empty');
                }

                return $role;
            });
            $questions['role'] = $question;
        }
        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
