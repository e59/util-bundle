<?php

namespace Cangulo\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKeyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('generate:key')
            ->setDescription('Generates a new key for parameters.yml');
    }

    protected function generateRandomSecret()
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            return hash('sha1', openssl_random_pseudo_bytes(23));
        }
        return hash('sha1', uniqid(rand(), true));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln($this->generateRandomSecret());
        $output->writeln('');
    }

}
