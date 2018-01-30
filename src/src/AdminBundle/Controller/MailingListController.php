<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Form\MailingListFilterForm;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class MailingListController extends Controller
{
    /**
     * @Route("/mailing_list/{page}", name="mailing_list", defaults={"page"=1})
     * @Method("GET")
     */
    public function indexAction($page, Request $request)
    {
        $numItemsPerPage = 3;
        $countPages = 0;
        $mailingList = [];
        $em = $this->getDoctrine()->getManager();
        $repositoryAttendee = $this->getDoctrine()->getRepository(Attendee::class);
        $defaultData = array('nameEmail' => '', 'event' => '', 'isSubscribed' => false);
        $form = $this->createForm(MailingListFilterForm::class, $defaultData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->filterIsNotEmpty($form)) {
            $searchedNameEmail = $form->getData()['nameEmail'];
            $searchedEvent = $form->getData()['event'];
            $searchedIsSubscribed = $form->getData()['isSubscribed'];

            if ('' != $searchedEvent) {
                $mailingList = $repositoryAttendee
                                ->getAttendeesFilteredByEvent(
                                        $searchedNameEmail,
                                        $searchedEvent,
                                        $searchedIsSubscribed,
                                        $numItemsPerPage,
                                        $page
                                    );
                $countPages = ceil( $repositoryAttendee
                                    ->getCountOfAttendeesFilteredByEvent(
                                        $searchedNameEmail,
                                        $searchedEvent,
                                        $searchedIsSubscribed
                                    ) / $numItemsPerPage);
            } else {
                $mailingList = $repositoryAttendee
                                ->getAttendeesFilteredWithoutEvent(
                                        $searchedNameEmail,
                                        $searchedIsSubscribed,
                                        $numItemsPerPage,
                                        $page
                                    );

                $countPages = ceil(($repositoryAttendee
                                    ->getCountOfAttendeesFilteredWithoutEvent(
                                        $searchedNameEmail,
                                        $searchedIsSubscribed
                                    ) / $numItemsPerPage));
            }

        } else {
            $mailingList = $repositoryAttendee->findBy(array(), array(), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
            $countPages = ceil($repositoryAttendee->getSelectCountAllAttendees() / $numItemsPerPage);
        }

        return $this->render('AdminBundle:MailingList:index.html.twig', array(
            'form' => $form->createView(),
            'mailingList' => $mailingList,
            'currentPage' => $page,
            'countPages' => $countPages,
            'numItemsPerPage' => $numItemsPerPage,
        ));
    }

    private function filterIsNotEmpty($form)
    {
        return ('' != $form->getData()['nameEmail'])
                || ('' != $form->getData()['event'])
                || ('' != $form->getData()['isSubscribed']);
    }
}
