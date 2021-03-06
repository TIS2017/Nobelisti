<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\LanguageForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends Controller
{
    /**
     * @Route("/languages", name="languages")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Language::class);
        $languages = $repository->findAll();

        return $this->render('AdminBundle:Language:index.html.twig', array(
            'languages' => $languages,
        ));
    }

    /**
     * @Route("/languages/add", name="languages_add")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $newLanguage = new Language();
        $form = $this->createForm(LanguageForm::class, $newLanguage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newLanguage);
            $em->flush();

            return $this->redirectToRoute('languages');
        }

        return $this->render('AdminBundle:Language:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/languages/edit/{id}", name="languages_edit", requirements={"id"="\d+"})
     * @Method({"GET","POST"})
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $language = $em->find(Language::class, $id);

        if (!$language) {
            throw $this->createNotFoundException(
                'No language found for id '.$id
            );
        }

        $form = $this->createForm(LanguageForm::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('languages');
        }

        return $this->render('AdminBundle:Language:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/languages/delete/{id}", name="languages_delete", requirements={"id"="\d+"})
     * @Method("POST")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $language = $em->find(Language::class, $id);

        if (!$language) {
            throw $this->createNotFoundException(
                'No language found for id '.$id
            );
        }

        $em->remove($language);
        $em->flush();

        return $this->redirectToRoute('languages');
    }
}
