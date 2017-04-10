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
        $errors   = array();


        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $repo = $this->getDoctrine()->getManager()->getRepository("AppBundle:Site");
            $existing = $repo->retrieveSiteByToken($site->getToken());

            if(!$existing){

                $em = $this->getDoctrine()->getManager();
                $em->persist($site);
                $em->flush();

                $messages[]  = "Your site was added succefully.";
            } else {
                $errors[] = "That domain is already registered.";
            }

        }



        return $this->render(
            'default/index.html.twig',
            array('form' => $form->createView(), "messages" => $messages, "errors"=> $errors)
        );



    }

    /**
     * @Route("/page1", name="page-one")
     */
    public function pageOneAction(Request $request)
    {

        return $this->render(
            'default/page.html.twig',
            array("token"=> md5($request->getSchemeAndHttpHost()))
        );

    }

    /**
     * @Route("/page2", name="page-two")
     */
    public function pageTwoAction(Request $request)
    {

        return $this->render(
            'default/page.html.twig',
            array("token"=> md5($request->getSchemeAndHttpHost()))
        );

    }

}
