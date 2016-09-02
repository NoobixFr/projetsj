<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller
{
    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('::base.html.twig');
    }

    /**
     * * @Route("/bootstrap-memo", name="bootstrap_memo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showBootStrapMemoAction(){
        $codeContent = file_get_contents(__DIR__."/../../../web/documents/code_bootstrap.txt");
        return $this->render(':static:bootstrap_memo.html.twig', array('code'=> $codeContent));
    }

    /**
     * @Route("/cv", name="cv")
     */
    public function showCvAction(){
        return $this->render(':static:cv.html.twig');
    }

    /**
     * @Route("/cv/pdf", name="cv_pdf")
     */
    public function showCvPdfAction(){
        return $this->render(':static:cv_pdf.html.twig');
    }
}
