<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\AdminCreateForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Entity\Admin;
use AdminBundle\Form\AdminEditFieldsForm;
use AdminBundle\Form\AdminEditPasswordForm;

class AdminController extends Controller
{
    /**
     * @Route("/admins", name="admins")
     * @Method("GET")
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository(Admin::class);
        $adminResult = $repo->findAll();

        return $this->render('AdminBundle:Admin:index.html.twig', array(
            'admins' => $adminResult,
        ));
    }

    private function hashPassword($admin, $password)
    {
        $encoded = $this->get('security.password_encoder')->encodePassword($admin, $password);
        $admin->setPassword($encoded);
    }

    /**
     * @Route("/admins/add", name="admins_add")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $admin = new Admin();

        $form = $this->createForm(AdminCreateForm::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->hashPassword($admin, $form->getData()->getPassword());

            $repo = $this->getDoctrine()->getManager();
            $repo->persist($admin);
            $repo->flush();

            return $this->redirectToRoute('admins');
        }

        return $this->render('AdminBundle:Admin:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admins/edit/{id}", name="admins_edit", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager();
        $admin = $repo->find(Admin::class, $request->get('id'));
        if (!$admin) {
            throw $this->createNotFoundException('Admin does not exist');
        }

        $formForFields = $this->getHandledChangeFieldsForm($admin, $request);
        $formChangePassword = $this->getHandledPasswordForm($admin, $request);

        if (null == $formForFields || null == $formChangePassword) {
            return $this->redirectToRoute('admins');
        }

        return $this->render('AdminBundle:Admin:edit.html.twig', array(
            'formForFields' => $formForFields->createView(),
            'formForPassword' => $formChangePassword->createView(),
        ));
    }

    private function getHandledChangeFieldsForm($admin, $request)
    {
        $form = $this->createForm(AdminEditFieldsForm::class, $admin);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $form;
        }

        $repo = $this->getDoctrine()->getManager();
        $repo->persist($admin);
        $repo->flush();

        return null;
    }

    private function getHandledPasswordForm($admin, $request)
    {
        $form = $this->createForm(AdminEditPasswordForm::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $form;
        }

        $data = $form->getData();

        $hashedPassword = $this->get('security.password_encoder')->encodePassword($admin, $data['oldPassword']);
        if ($hashedPassword != $admin->getPassword()) {
            $form->get('oldPassword')->addError(new FormError('Old password is not correct'));

            return $form;
        }

        $this->hashPassword($admin, $data['password']);

        $repo = $this->getDoctrine()->getManager();
        $repo->persist($admin);
        $repo->flush();

        return null;
    }

    /**
     * @Route("admins/delete/{id}", name="admins_delete", requirements={"id"="\d+"})
     * @Method("POST")
     */
    public function deleteAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getManager();
        $admin = $repo->find(Admin::class, $id);
        if (!$admin) {
            throw $this->createNotFoundException('Admin does not exist.');
        }

        $repo->remove($admin);
        $repo->flush();

        return $this->redirectToRoute('admins');
    }
}
