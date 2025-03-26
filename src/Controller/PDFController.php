<?php

namespace App\Controller;


use App\Service\readDataFromFile;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PDFController extends AbstractController
{
    private $path;
    public function __construct(
        private ReadDataFromFile $readDataFromFile,
        private ParameterBagInterface $parameterBag)
    {
        $this->path = $this->parameterBag->get('path');
    }

    #[Route('/{_locale}/pdf', name: 'pdf')]
    #[IsGranted('ROLE_USER')]
    public function generatePdf() : Response
    {
        $options=new Options();
        $options->set('defaultFont', 'Times New Roman');
        $dompdf=new Dompdf($options);

        $data=$this->readDataFromFile->readDataFromFile($this->path);
        $today=new \DateTime();
        $today=$today->format('Y-m-d');
        $html=$this->renderView('PDF/activityPDF.html.twig',[
            'data'=>$data,
            'today'=>$today,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();


        $outputPath=sys_get_temp_dir().'/'.uniqid().'.pdf';
        file_put_contents($outputPath,$dompdf->output());
        $response = new BinaryFileResponse($outputPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'pdfUserActivity.pdf');
        //return $response;
        return $this->file($outputPath);
//
//        return  new Response(
//            $dompdf->output(),
//            200,
//            [
//                'Content-Type' => 'application/pdf',
//                'Content-Disposition' => 'attachment; filename="pdfUserActivity.pdf",
//            ]
//
//        );

    }
}