<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AdminBundle\Entity\Organizer;

class OrganizerController extends Controller
{
    /**
     * @Route("/organizers", name="organizers")
     * @Method("GET")
     */
    public function organizerAction(Request $request){

        $defaultData = array('email' => '');

        $form = $this->createFormBuilder($defaultData)
            ->add('email', TextType::class)
            ->add('filter', SubmitType::class, array('label' => 'Filter'))
            ->setMethod('GET')
            ->getForm();

        $form->handleRequest($request);

        $organizers = [];

       if ($form->isSubmitted() && $form->isValid()) {

            $searchedEmail = $form->getData()['email'];

            $organizers = $this->getDoctrine()
                ->getRepository(Organizer::class)
                ->findBy(array('email' => $searchedEmail));
        } else {

            $organizers = $this->getDoctrine()
                ->getRepository(Organizer::class)
                ->findAll();
        }

        return $this->render('AdminBundle:Organizers:organizers.html.twig', array(
            'form' => $form->createView(),
            'organizers' => $organizers
        ));
    }

}
