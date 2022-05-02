<?php

declare(strict_types=1);

namespace Talav\UserBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Talav\Component\User\Manager\UserManagerInterface;

class DemoteUserCommand extends AbstractRoleCommand
{
    protected static $defaultName = 'talav:user:demote';

    protected function configure()
    {
        parent::configure();
        $this
            ->setName('talav:user:demote')
            ->setDescription('Demote a user by removing a role')
            ->setHelp(
                <<<'EOT'
The <info>fos:user:demote</info> command demotes a user by removing a role
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
            $userManager->demote($username);
            $output->writeln(
                sprintf(
                    'User "%s" has been demoted as a simple user. This change will not apply until the user logs out and back in again.',
                    $username
                )
            );
        } else {
            if ($userManager->removeRole($username, $role)) {
                $output->writeln(
                    sprintf(
                        'Role "%s" has been removed from user "%s". This change will not apply until the user logs out and back in again.',
                        $role,
                        $username
                    )
                );
            } else {
                $output->writeln(sprintf('User "%s" didn\'t have "%s" role.', $username, $role));
            }
        }
    }
}
