<?php
/**
 * Created by PhpStorm.
 * User: Noobix
 * Date: 11/06/2016
 * Time: 10:17
 */

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexionAction(){
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('show_articles');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render(':user:connexion.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
    
    /**
     * @Route("/secured/home", name="admin_homepage")
     */
    public function adminHomepageAction(){
        return new Response("Notre propre Hello World !");
    }
}