<?php

declare(strict_types=1);

namespace Talav\UserBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Talav\Component\User\Manager\UserManagerInterface;

class PromoteUserCommand extends AbstractRoleCommand
{
    protected static $defaultName = 'talav:user:promote';

    protected function configure()
    {
        parent::configure();
        $this
            ->setName('talav:user:promote')
            ->setDescription('Promotes a user by adding a role')
            ->setHelp(
                <<<'EOT'
The <info>talav:user:promote</info> command promotes a user by adding a role
  <info>php %command.full_name% matthieu ROLE_CUSTOM</info>
  <info>php %command.full_name% --super matthieu</info>
EOT
            );
    }

    protected function executeRoleCommand(
        UserManagerInterface $userManager,
        OutputInterface $output,
        $username,
        $super,
        $role
    ) {
        if ($super) {
            $userManager->promote($username);
            $output->writeln(
                sprintf(
                    'User "%s" has been promoted as a super administrator. This change will not apply until the user logs out and back in again.',
                    $username
                )
            );
        } else {
            if ($userManager->addRole($username, $role)) {
                $output->writeln(
                    sprintf(
                        'Role "%s" has been added to user "%s". This change will not apply until the user logs out and back in again.',
                        $role,
                        $username
                    )
                );
            } else {
                $output->writeln(sprintf('User "%s" did already have "%s" role.', $username, $role));
            }
        }
    }
}
