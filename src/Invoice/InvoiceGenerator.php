<?php

namespace App\Invoice;

use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
// Include Dompdf required namespaces
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass;
use Twig\Environment;

class InvoiceGenerator
{
    protected $reservationRepository;
    protected $twig;
    protected $roomRepository;
    public function __construct(ReservationRepository $reservationRepository, Environment $twig, RoomRepository $roomRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->twig = $twig;
        $this->roomRepository = $roomRepository;
    }
    /**
     * @Route("/invoice", name="app_invoice")
     */
    public function generateInvoice($resaID)
    {

        // Invoice no, 
        // reservation details 
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file

        $reservation = $this->reservationRepository->find($resaID);
        $room = $this->roomRepository->findByExampleField($reservation->getRoomNo());

        $html = $this->twig->render('front/invoice/index.html.twig', [
            'title' => "INVOICE",
            'reservation' => $reservation,
            'room' => $room
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $publicDirectory = getcwd();
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/Invoice.pdf';

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        // Send some text response
        return $pdfFilepath;
    }
}
