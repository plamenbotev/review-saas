<?php
/**
 * Created by PhpStorm.
 * User: botev
 * Date: 09.04.17
 * Time: 15:38
 */

namespace AppBundle\EventListener;


use AppBundle\Controller\TokenAuthenticatedController;
use AppBundle\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class TokenListener
{

    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;

    }
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {

            $request = $event->getRequest();

            $token = $request->get("token");

            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : array());
            }

            $siteRepo = $this->doctrine->getRepository("AppBundle:Site");

            $site = $siteRepo->retrieveSiteByToken($token);


            if (!($site instanceof Site)) {

                throw new AccessDeniedHttpException('This action needs a valid token!');
            }

            //inject the authenticated site entity.
            $controller[0]->setCurrentSite($site);


        }
    }
}
