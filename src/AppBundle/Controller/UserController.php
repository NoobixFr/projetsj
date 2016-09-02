<?php
/**
 * Created by PhpStorm.
 * User: Noobix
 * Date: 05/06/2016
 * Time: 17:39
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
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

}