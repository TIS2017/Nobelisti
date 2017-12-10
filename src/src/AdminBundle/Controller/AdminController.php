<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Entity\Admin;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $email = $request->get('email');
        $password = $request->get('password');
        $password1 = $request->get('password1');

        if ($password == "" or $password1 == "") {
            $error = "Enter password 2x, please";
            return $this->render('AdminBundle:Admin:create.html.twig', array(
                'error' => $error
            ));
        }

        if ($password != $password1) {
            $error = "Passwords do not match";
            return $this->render('AdminBundle:Admin:create.html.twig', array(
                'error' => $error
            ));
        }

        $admin = new Admin($email, $password);
        $this->hashPassword($admin, $password);
        $repo = $this->getDoctrine()->getManager();
        $repo->persist($admin);
        $repo->flush();
        return $this->redirectToRoute('admins');
    }

    /**
     * @Route("/admins/create", name="create", methods={"GET"})
     */
    public function getAdminForm(Request $request) {
        return $this->render('AdminBundle:Admin:create.html.twig', array( 'error' => null));
    }
}
