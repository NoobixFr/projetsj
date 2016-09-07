<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller
{

    /**
     * Affiche la page du memento bootstrap
     * @Route("/bootstrap-memo", name="bootstrap_memo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showBootStrapMemoAction(){
        $codeContent = file_get_contents(__DIR__."/../../../web/documents/code_bootstrap.txt");
        return $this->render(':static:bootstrap_memo.html.twig', array('code'=> $codeContent));
    }

    /**
     * Affiche la page du cv au format HTML
     * @Route("/cv", name="cv")
     */
    public function showCvAction(){
        return $this->render(':static:cv.html.twig');
    }

    /**
     * Affiche le pdf du cv.
     * le pdf se trouve dans le dossier : web/documents/DUFRESNE_Jonathan_cv.pdf
     * @Route("/cv/pdf", name="cv_pdf")
     */
    public function showCvPdfAction(){
        return $this->render(':static:cv_pdf.html.twig');
    }
}
