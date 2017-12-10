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

    public function hashPassword($admin, $password) {
        $encoded = $this->get('security.password_encoder')->encodePassword($admin, $password);
        $admin->setPassword($encoded);
    }

    /**
     * @Route("/admins/create", methods={"POST"})
     */
    public function createAdmin(Request $request) {
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

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with, "email", and "password" keys
            $data = $form->getData();
            $email = $data['email'];
            $password = $data['password'];

            $admin = new Admin($email, $password);
            $this->hashPassword($admin, $password);
            $repo = $this->getDoctrine()->getManager();
            $repo->persist($admin);
            $repo->flush();
            return $this->redirectToRoute('admins');
        }
        return $this->render('AdminBundle:Admin:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admins/create", name="create", methods={"GET"})
     */
    public function getAdminForm(Request $request) {
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

        return $this->render('AdminBundle:Admin:create.html.twig', array('form' => $form->createView()));
    }
}
