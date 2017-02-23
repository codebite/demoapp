<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 18.02.2017
 * Time: 19:46
 */

namespace AppBundle\Controller;

use AppBundle\Benchmark\Benchmark;
use AppBundle\Benchmark\BenchmarkSet;
use AppBundle\Form\BenchmarkSetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BenchmarkController
 * @package AppBundle\Controller
 */
class BenchmarkController extends Controller
{
    /**
     * Action displays benchmark form
     *
     * @param Request $request
     * @return ContainerInterface
     * @Route("/benchmark/set", name="benchmark-set")
     */
    public function setAction(Request $request)
    {
        //prepare benchmark set
        $benchmarkSet = new BenchmarkSet();
        $form = $this->createForm(BenchmarkSetType::class, $benchmarkSet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //save benchmark set in the session
            $session = $request->getSession();
            $session->set('benchmarkSet', $form->getData());
            //redirect to page displaying the results
            return $this->redirectToRoute('benchmark-run');
        }

        return $this->render('benchmark/set.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Action for results calculation and presentation
     *
     * @Route("/benchmark/run", name="benchmark-run")
     */
    public function runAction(Request $request)
    {
        //gets the benchmark set from the session and clear session data
        $session = $request->getSession();
        $benchmarkSet = $session->get('benchmarkSet');
        $session->clear();

        if(!$benchmarkSet){
            return $this->redirectToRoute('benchmark-set');
        }

        //run benchmark tests on prepared set
        $benchmark = new Benchmark($benchmarkSet);
        $benchmarkResult = $benchmark->run()->createBenchmarkResult();

        //get benchmark results
        $benchmarkedUrlResult = $benchmarkResult->getBenchmarkedUrlResult();
        $competitorsResults = $benchmarkResult->getCompetitorsResults();

        //send mail alerts if benchmarked url is slower than competitors
        if($benchmarkedUrlResult->isSlowerThanCompetitors){
            $alertService = $this->get('app.benchmark_alert');
            $alertService->sendAlert($benchmarkSet->getBenchmarkedWebsite());
        }

        //send sms alerts if benchmarked url is twice slower than competitors
        if($benchmarkedUrlResult->isTwiceSlowerThanCompetitors){
            $smsAlert = $this->get('app.benchmark_sms_alert');
            $smsAlert->sendAlert("Benchmarked website {$benchmarkSet->getBenchmarkedWebsite()} is twice slower than competitors!");
        }

        //create report
        $reportService = $this->get('app.report_service');
        $reportService->createReport($benchmarkResult);

        //display results
        return $this->render('benchmark/run.html.twig', array(
            'benchmarkSet' => $benchmarkSet,
            'benchmarkedUrlResult' => $benchmarkedUrlResult,
            'competitorsResults' => $competitorsResults
        ));
    }
}