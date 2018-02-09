<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Registration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Datetime;

class ConfirmRegistrationController extends Controller
{
    /**
     * @Route("/successfullConfirmation/{token}", name="confirm_registration")
     */

    public function indexAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $registration = $em->getRepository(Registration::class)->findOneBy(array('confirmationToken' => $token));

        // TODO if not confirmed
        if ($registration != null) {
            $date = new \DateTime('now');
            $registration->setConfirmed($date);
            $em->persist($registration);
            $em->flush();

            return $this->render('AppBundle:confirmedRegistration.html.twig');
        }
        return; //TODO
    }
}
