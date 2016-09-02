<?php
/**
 * Created by PhpStorm.
 * User: Noobix
 * Date: 20/06/2016
 * Time: 19:48
 */

namespace BackendBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BackendController extends Controller
{
    /**
     * @Route("/secured/dashboard",name="backend_dashbord")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function dashbordAction(){
        $numberOfNewComments = count($this->getDoctrine()->getManager()->getRepository("AppBundle:Comment")->findBy(array('new' => true)));
        return $this->render('BackendBundle:default:index.html.twig', array('numberOfNewComments' => $numberOfNewComments));
    }
}