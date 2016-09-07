<?php
// src/AppBundle/Controller/WidgetController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WidgetController extends Controller
{
    /**
     * Permet de récupérer la météo de la ville configuré dans le fichier de app/config/config.yml
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function widgetMeteoAction(){
        $url = "http://www.prevision-meteo.ch/services/json/". $this->getParameter('meteo.city');

        $content = @file_get_contents($url);

        return $this->render("widget/meteo.html.twig",
            array(
                "meteo" => json_decode($content)
            )
        );
    }
}