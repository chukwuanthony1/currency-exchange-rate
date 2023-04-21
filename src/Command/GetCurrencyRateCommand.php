<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the "name" and "description" arguments of AsCommand replace the
// static $defaultName and $defaultDescription properties
#[AsCommand(
    name: 'app:currency:rates',
    description: 'Fetch the currency exchange rates',
    hidden: false,
    aliases: ['app:currency:rates']
)]
class GetCurrencyRateCommand extends Command
{

    protected function configure(): void
    {
        $this
            // configure an argument
            ->addArgument('base_currency', InputArgument::REQUIRED, 'Base Currency')
            ->addArgument('target_currencies', InputArgument::IS_ARRAY, 'Array of target currencies');
            // ...
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Fetching Exchange Rates for Base Currency: ' . $input->getArgument('base_currency'));

        for ($i = 0; $i < count($input->getArgument('target_currencies')); $i++) {
            $output->writeln('target_currency: ' . $input->getArgument('target_currencies')[$i]);
        }

        return Command::SUCCESS;
    }
}
