<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LanguageController extends Controller
{
    /**
     * @Route("/languages", name="languages")
     * @Method("GET")
     */
    public function indexAction(Request $request){

        $languages = [];
        $repository = $this->getDoctrine()->getRepository(Language::class);
        $languages = $repository->findAll();

        return $this->render('AdminBundle:Language:index.html.twig', array(
            'languages' => $languages
        ));
    }

    /**
     * @Route("/languages/add", name="languages_add")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request){

        $newLanguage= new Language();
        $form = $this->getForm($newLanguage);

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
     * @Route("/languages/edit/{id}", name="languages_edit")
     * @Method({"GET","POST"})
     */
    public function editAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository(Language::class)->findOneBy(array('id' => $id));

        if (!$language) {
            throw $this->createNotFoundException(
                'No language found for id '.$id
            );
        }

        $form = $this->getForm($language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('languages');
        } 

        return $this->render('AdminBundle:Language:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function getForm($data){

        return $this->createFormBuilder($data)
            ->add('language', TextType::class)
            ->add('code', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setMethod('POST')
            ->getForm();
    }

    /**
     * @Route("/languages/delete/{id}", name="languages_delete")
     * @Method("POST")
     */
    public function deleteAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository(Language::class)->findOneBy(array('id' => $id));

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