<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Entity\Admin;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminController extends Controller {

    /**
     * @Route("/admins", name="admins")
     */
    public function indexAction() {
        $repo = $this->getDoctrine()->getRepository('AdminBundle:Admin');
        $adminResult = $repo->findAll();
        return $this->render('AdminBundle:Admin:index.html.twig', array(
            'admins' => $adminResult
        ));
    }

    private function hashPassword($admin, $password) {
        $encoded = $this->get('security.password_encoder')->encodePassword($admin, $password);
        $admin->setPassword($encoded);
    }

    private function getNewAdminForm($request) {
        $defaultData = array(
            'email' => '',
            'password' => '',
            'password1' => ''
        );

        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        return $form;
    }

    /**
     * @Route("/admins/create", methods={"POST"})
     */
    public function createAdmin(Request $request) {
        $form = $this->getNewAdminForm($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with, "email", and "password" keys
            $data = $form->getData();
            $admin = new Admin($data['email']);
            $this->hashPassword($admin, $data['password']);

            //validate
            $validator = $this->get('validator');
            $errors = $validator->validate($admin);
            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                //TODO: implement __toString()
                $errorsString = (string) $errors;

                return $this->render('AdminBundle:Admin:create.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $errorsString
                ));
            }


            $repo = $this->getDoctrine()->getManager();
            $repo->persist($admin);
            $repo->flush();
            return $this->redirectToRoute('admins');
        }
        return $this->render('AdminBundle:Admin:create.html.twig', array(
            'form' => $form->createView(),
            'errors' => null
        ));
    }

    /**
     * @Route("/admins/create", name="admins_create", methods={"GET"})
     */
    public function createAdminForm(Request $request) {
        $form = $this->getNewAdminForm($request);

        return $this->render('AdminBundle:Admin:create.html.twig', array(
            'form' => $form->createView(),
            'errors' => null
        ));
    }

    private function getEditAdminFormForFields($request, $adminObject) {
        $defaultData = array(
            'email' => $adminObject->getEmail(),
        );

        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        return $form;
    }

    private function getEditAdminFormForPassword($request) {
        $defaultData = array(
            'oldPassword' => '',
            'password' => '',
            'password1' => ''
        );

        $form = $this->createFormBuilder($defaultData)
            ->add('oldPassword', PasswordType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        return $form;
    }

    /**
     * @Route("/admins/edit/{id}", name="admins_edit", methods={"GET"})
     */
    public function editAdminForm(Request $request) {
        $adminId = $request->get('id');
        $repo = $this->getDoctrine()->getManager();

        $admin = $repo->find(Admin::class, $adminId);
        if (!$admin) {
            throw $this->createNotFoundException('Admin does not exist');
        }

        $formForFields = $this->getEditAdminFormForFields($request, $admin);
        $formChangePassword = $this->getEditAdminFormForPassword($request);

        return $this->render('AdminBundle:Admin:edit.html.twig', array(
            'formForFields' => $formForFields->createView(),
            'formForPassword' => $formChangePassword->createView(),
            'errors' => null
        ));
    }

    /**
     * @Route("/admins/edit/{id}", methods={"POST"})
     */
    public function editAdmin(Request $request) {
        $repo = $this->getDoctrine()->getManager();
        $adminId = $request->get('id');
        $admin = $repo->find(Admin::class, $adminId);
        if (!$admin) {
            throw $this->createNotFoundException('Admin does not exist');
        }

        $formForFields = $this->getEditAdminFormForFields($request, $admin);
        $formChangePassword = $this->getEditAdminFormForPassword($request);

        if ($formForFields->isSubmitted() && $formForFields->isValid()) {
            $data = $formForFields->getData();
            $admin->setEmail($data['email']);

            //validate
            $validator = $this->get('validator');
            $errors = $validator->validate($admin);
            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                //TODO: implement __toString()
                $errorsString = (string) $errors;

                return $this->render('AdminBundle:Admin:edit.html.twig', array(
                    'form' => $formForFields->createView(),
                    'errors' => $errorsString
                ));
            }

            $repo = $this->getDoctrine()->getManager();
            $repo->persist($admin);
            $repo->flush();
            return $this->redirectToRoute('admins');
        }

        if ($formChangePassword->isSubmitted() && $formChangePassword->isValid()) {
            //TODO:porovnaj hesla
        }

        return $this->render('AdminBundle:Admin:edit.html.twig', array(
            'formForFields' => $formForFields->createView(),
            'formForPassword' => $formChangePassword->createView(),
            'errors' => null
        ));
    }

    /**
     * @Route("admins/delete/{id}", name="admins_delete", methods={"POST"})
     */
    public function deleteAdmin(Request $request) {
        $repo = $this->getDoctrine()->getManager();
        $adminId = $request->get('id');
        $admin = $repo->find(Admin::class, $adminId);
        if (!$admin) {
            throw $this->createNotFoundException('Admin does not exist.');
        }

        $repo->remove($admin);
        $repo->flush();
        return $this->redirectToRoute('admins');
    }
}
