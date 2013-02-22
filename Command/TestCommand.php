<?php
namespace Lasso\Oauth2ClientBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lasso:oauth2:test')
            ->setDescription('Test the oauth2 client');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->getContainer()->get('lasso_oauth2_client.client'));
        $output->writeln('testing');
    }
}
