<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Form\SiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

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

            return $this->redirectToRoute('replace_with_some_route');
        }

        return $this->render(
            'default/index.html.twig',
            array('form' => $form->createView())
        );



    }



    public function getReactionsByObject(Request $request, $domain, $object){

    }

    public function setReactionByUserAndObject(Request $request,$domain, $email, $object){

    }

}
