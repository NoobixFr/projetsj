<?php
/**
 * Created by PhpStorm.
 * User: Noobix
 * Date: 20/06/2016
 * Time: 10:58
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WidgetController extends Controller
{
    
    public function widgetMeteoAction(){

        $url = "http://www.prevision-meteo.ch/services/json/". $this->getParameter('meteo.city');
        if(file_exists($url)){
            $content = file_get_contents(file_get_contents($url));
        }else{
            $content = false;
        }

        return $this->render("widget/meteo.html.twig",
            array(
                "meteo" => json_decode($content)
            )
        );
    }
}