<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreditUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:user:credit')
            ->setDescription('Add some game credits to the user.')
            ->addArgument('username', InputArgument::REQUIRED, 'The user account')
            ->addArgument('credits', InputArgument::REQUIRED, 'The number of credits')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('AppBundle:User');
        
        $user = $repository->findOneBy([ 'username' => $input->getArgument('username') ]);
        if (!$user instanceof User) {
            throw new \RuntimeException('Cannot find matching User entity.');
        }

        if (OutputInterface::VERBOSITY_VERY_VERBOSE === $output->getVerbosity()) {
            $container->get('logger')->log('debug', 'User found!!!!');
        }

        $user->credit($input->getArgument('credits'));
        $em->flush();

        $table = new Table($output);
        $table
            ->setHeaders([ 'Key', 'Value' ])
            ->addRow([ 'Username', $user->getUsername() ])
            ->addRow([ 'Credits', $user->getCreditsBalance() ])
            ->render()
        ;
    }
}
