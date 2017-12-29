<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class EventController extends Controller
{
    //todo

    /**
     * @Route("/zatialnedomyslenarouta", name="event_edit")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Event:index.html.twig');
    }
}
