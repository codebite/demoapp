<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 20.02.2017
 * Time: 20:12
 */

namespace AppBundle\Command;

use AppBundle\Benchmark\Benchmark;
use AppBundle\Benchmark\BenchmarkSet;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validation;

/**
 * Benchmark Command class for benchmarking websites.
 * Usage: php bin/console app:benchmark-url http://example.com http://example2.com http://example2.com
 * Arguments should be separated by space char
 *
 * Class BenchmarkUrlCommand
 * @package AppBundle\Command
 */
class BenchmarkUrlCommand extends ContainerAwareCommand
{
    /**
     * Command preparation
     */
    protected function configure()
    {
        $this
            ->setName('app:benchmark-url')
            ->setDescription('Measures website response time and comperes result with competitors.')
            ->setHelp('This command allows you to check website response time')
            ->addArgument('url', InputArgument::REQUIRED, 'The website url.')
            ->addArgument(
                'competitors',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'What websites do you want to compare with (separate multiple names with a space)?'
            );
    }

    /**
     * Command execution
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //get the command arguments
        $benchmarkedUrl = $input->getArgument('url');
        $competitors = $input->getArgument('competitors');

        //arguments validation
        if(!$this->validBenchmarkedUrl($benchmarkedUrl) OR !$this->validCompetitorsUrl($competitors)){
            throw new InvalidParameterException('Incorrect arguments! Valid, full urls names are required!');
        }

        //prepare benchmark set
        $benchmarkSet = new BenchmarkSet();
        $benchmarkSet->setBenchmarkedWebsite($benchmarkedUrl);
        $benchmarkSet->setCompetitorsWebsites($competitors);

        //run benchmark tests on prepared set
        $benchmark = new Benchmark($benchmarkSet);
        $benchmarkResult = $benchmark->run()->createBenchmarkResult();

        //get benchmark results
        $benchmarkedUrlResult = $benchmarkResult->getBenchmarkedUrlResult();
        $competitorsResults = $benchmarkResult->getCompetitorsResults();

        //send mail alerts if benchmarked url is slower than competitors
        if($benchmarkedUrlResult->isSlowerThanCompetitors){
            $alertService = $this->getContainer()->get('app.benchmark_alert');
            $alertService->sendAlert($benchmarkedUrl);
            $output->writeln('Mail alert sent!');
        }

        //send sms alerts if benchmarked url is twice slower than competitors
        if($benchmarkedUrlResult->isTwiceSlowerThanCompetitors){
            $smsAlert = $this->getContainer()->get('app.benchmark_sms_alert');
            $smsAlert->sendAlert("Benchmarked website {$benchmarkedUrl} is twice slower than competitors!");
            $output->writeln('Sms alert sent!');
        }

        //save benchmark results to log file
        $reportService = $this->getContainer()->get('app.report_service');
        $reportService->createReport($benchmarkResult);

        //display results
        $output->writeln('');
        $output->writeln('Benchmarked website url: ' . $benchmarkedUrl);
        $output->writeln('Results:');

        $table = new Table($output);
        $table->setHeaders(array('Website url', 'Website response time [ms]', 'time difference [ms]'));
        $table->setRows(array(
                array($benchmarkedUrl, $benchmarkedUrlResult->responseTime, ''),
                new TableSeparator()
        ));

        //adding competitors results to table
        foreach ($competitorsResults as $compResult){
            $timeDiff = $benchmarkedUrlResult->responseTime - $compResult->responseTime;
            $table->addRow(array($compResult->url, $compResult->responseTime, $timeDiff));
        }
        $table->render();
    }

    protected function validBenchmarkedUrl($url)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($url, array(
            new Url(),
            new NotBlank(),
        ));
        if (0 !== count($violations)) {
            return false;
        }
        return true;
    }

    protected function validCompetitorsUrl($competitors)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($competitors, array(
            new All([new Url(), new NotBlank()])
        ));
        if (0 !== count($violations)) {
            return false;
        }
        return true;
    }


}