<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use PhpParser\Node\Stmt\Label;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChartController extends AbstractController
{
    /**
     * @Route("/chart", name="admin_chart")
     */
    public function chart(ChartBuilderInterface $chartBuilder): Response
    { //not used




        $datapoints = [
            '0' => (object) ['label' => "Jan", 'y' => 10],
            '1' => (object) ['label' => "feb", 'y' => 15]
        ];

        $datapoints = json_encode($datapoints);

        return $this->render('back/chart/chart.html.twig', [
            'datapoints' => $datapoints
        ]);
    }

    /**
     * @Route("/chart/{roomNo}", name="admin_chart_index")
     */
    public function index($roomNo, ChartBuilderInterface $chartBuilder, ReservationRepository $reservationRepository)
    {

        $reservations = $reservationRepository->findAllByRoomNo($roomNo);

        function daysCalcul($checkIn, $checkOut)
        {
            if ($checkIn == $checkOut) {
                return 1;
            } else {
                return (date_diff($checkIn, $checkOut)->d + 1);
            }
        }
        $dataPoints = [];
        $janDiff = $febDiff = $marDiff = $aprilDiff = $mayDiff = $juneDiff = $julyDiff = $augDiff = $septDiff = $octDiff = $novDiff = $decDiff = 0;
        foreach ($reservations as $key => $reservation) {
            $resaMonth = date("m", strtotime($reservation->getCheckInDate()->format('Y-m-d')));
            $checkIn = $reservation->getCheckInDate();
            $checkOut = $reservation->getCheckOutDate();


            switch ($resaMonth) {
                case 1:
                    if ($checkIn == $checkOut) {
                        $janDiff++;
                    } else {

                        $janDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }

                    break;
                case 2:
                    if ($checkIn == $checkOut) {
                        $febDiff++;
                    } else {
                        $febDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }

                    break;
                case 3:
                    if ($checkIn == $checkOut) {
                        $marDiff++;
                    } else {
                        $marDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
                case 4:
                    if ($checkIn == $checkOut) {
                        $aprilDiff++;
                    } else {
                        $aprilDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
                case 5:
                    $mayDiff = $mayDiff + daysCalcul($checkIn, $checkOut);

                    break;
                case 6:
                    $juneDiff = $juneDiff + daysCalcul($checkIn, $checkOut);

                    break;
                case 7:
                    if ($checkIn == $checkOut) {
                        $julyDiff++;
                    } else {
                        $julyDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
                case 8:
                    if ($checkIn == $checkOut) {
                        $augDiff++;
                    } else {
                        $augDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
                case 9:
                    if ($checkIn == $checkOut) {
                        $septDiff++;
                    } else {
                        $septDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
                case 10:
                    if ($checkIn == $checkOut) {
                        $octDiff++;
                    } else {
                        $octDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
                case 11:
                    if ($checkIn == $checkOut) {
                        $novDiff++;
                    } else {
                        $novDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
                case 12:
                    if ($checkIn == $checkOut) {
                        $decDiff++;
                    } else {
                        $decDiff = date_diff($checkIn, $checkOut)->d + 1;
                    }
                    break;
            }
        }
        $dataPoints[] = array('label' => 'Janvier', 'y' => $janDiff);
        $dataPoints[] = array('label' => 'fevrier', 'y' => $febDiff);
        $dataPoints[] = array('label' => 'Mars', 'y' => $marDiff);
        $dataPoints[] = array('label' => 'Avril', 'y' => $aprilDiff);
        $dataPoints[] = array('label' => 'Mai', 'y' => $mayDiff);
        $dataPoints[] = array('label' => 'Juin', 'y' => $juneDiff);
        $dataPoints[] = array('label' => 'Juillet', 'y' => $julyDiff);
        $dataPoints[] = array('label' => 'Aout', 'y' => $augDiff);
        $dataPoints[] = array('label' => 'Septembre', 'y' => $septDiff);
        $dataPoints[] = array('label' => 'Octobre', 'y' => $octDiff);
        $dataPoints[] = array('label' => 'Novembre', 'y' => $novDiff);
        $dataPoints[] = array('label' => 'Decembre', 'y' => $decDiff);


        return $this->render('back/chart/index.html.twig', [
            'data' => json_encode($dataPoints)
        ]);
    }
}
