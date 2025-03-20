<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\PageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:get-currency-rates',
    description: 'Pobiera kursy walut ze strony i zapisuje w bazie danych',
)]
class GetCurrencyRatesCommand extends Command
{
    private PageService $pageService;

    public function __construct(PageService $pageService)
    {
        parent::__construct();
        $this->pageService = $pageService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = 'https://nbp.pl/statystyka-i-sprawozdawczosc/kursy/tabela-c/';
        $this->pageService->getTableData($url);
        $output->writeln('<info>Kursy walut zostały pobrane i zapisane do bazy danych</info>');
        return Command::SUCCESS;
    }
}
