<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
	/**
	 * @Route("/admins", name="admins")
	 */
	public function indexAction()
	{
		$repo = $this->getDoctrine()->getRepository('AdminBundle:Admin');
		$adminResult = $repo->findAll();

		return $this->render('AdminBundle:Admin:index.html.twig', array('admins' => $adminResult));
	}

	/**
	 * @Route("/admins/create", name="create")
	 */
	public function createAdmin(Request $request) {
		if ($request->isMethod('POST')) {
			$admin = new Admin();
			if ($form->isSubmitted() && $form->isValid()) {
				// perform some action...

				return $this->redirectToRoute('task_success');
			}
		}
		return $this->render('AdminBundle:Admin:create.html.twig');
	}
}
