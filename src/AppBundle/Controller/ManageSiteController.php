<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Form\SiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ManageSiteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {


        $messages = array();

        // 1) build the form
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = md5($site->getPassword().$this->getParameter("salt"));
            $site->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user


            $messages[]  = "Your site was added succefully.";


        }


        return $this->render(
            'default/index.html.twig',
            array('form' => $form->createView(), "messages" => $messages)
        );



    }

    /**
     * @Route("/page1", name="page-one")
     */
    public function pageOneAction(Request $request)
    {

        return $this->render(
            'default/page1.html.twig',
            array()
        );

    }

    /**
     * @Route("/page2", name="page-two")
     */
    public function pageTwoAction(Request $request)
    {

        return $this->render(
            'default/page1.html.twig',
            array()
        );

    }

}
