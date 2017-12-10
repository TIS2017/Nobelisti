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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

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

    private function getForm($request) {
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
        $form = $this->getForm($request);

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
     * @Route("/admins/create", name="create", methods={"GET"})
     */
    public function getAdminForm(Request $request) {
        $form = $this->getForm($request);

        return $this->render('AdminBundle:Admin:create.html.twig', array(
            'form' => $form->createView(),
            'errors' => null
        ));
    }
}
