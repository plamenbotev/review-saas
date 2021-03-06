<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Entity\SiteObject;
use AppBundle\Entity\User;
use AppBundle\Entity\UserReaction;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;

class DefaultController extends Controller implements TokenAuthenticatedController
{
    /**
     * @var Site
     */
    private $site;


    public function setCurrentSite(Site $site){

        $this->site = $site;

    }


    /**     *
     * @Route("/add-reaction/{token}", name="add_reaction")
     */
    public function addReaction(Request $request, $token){


        $res = [

            "success" => true,
            "errors"  => []

        ];


        try {

            //persist the data in transaction
            $this->getDoctrine()->getEntityManager()->transactional(function (EntityManager $em) use ($request, &$res) {


                $data = $request->request->all();


                $userRepo = $em->getRepository("AppBundle:User");
                $siteObjectRepo = $em->getRepository("AppBundle:SiteObject");
                $reactionRepo = $em->getRepository("AppBundle:UserReaction");



                $email = !empty($data['email']) ? $data['email'] : null;
                $url = !empty($data['url']) ? $data['url'] : null;
                $stars = !empty($data['stars']) ? $data['stars'] : null;
                $comment = !empty($data['comment']) ? $data['comment'] : null;

                $validator = $this->get("validator");


                $user = $userRepo->retrieveUserByMail($email);

                if(!$user) {

                    $user = new User();
                    $user->setEmail($email);
                }


                $siteObject = $siteObjectRepo->retrieveObjectByString($this->site, $url);

                if(!$siteObject) {

                    $siteObject = new SiteObject();

                    $siteObject->setSite($this->site);
                    $siteObject->setUrl($url);
                }

                $react = $reactionRepo->getReaction($siteObject, $user);

                if($react){

                    throw new Exception("User already reacted for that object.");

                }

                $reaction = new UserReaction();

                $reaction->setObject($siteObject);
                $reaction->setUser($user);
                $reaction->setStars($stars);
                $reaction->setComment($comment);


                foreach (array($user, $siteObject, $reaction) as $row) {

                    $violations = $validator->validate($row);



                    if (count($violations)) {


                        /**
                         * @var $row ConstraintViolationInterface
                         */
                        foreach ($violations as $row) {


                            $res["success"] = false;
                            $res["errors"][] = $row->getPropertyPath()." : ".$row->getMessage();

                        }


                    }

                }

                if($res["success"]){

                    $em->persist($user);
                    $em->persist($siteObject);
                    $em->persist($reaction);

                }


            });

        }catch (Exception $e){
            $res["success"]  = false;
            $res["errors"][] = $e->getMessage();
        }


        $response = $this->json($res);

        $response->headers->set('Access-Control-Allow-Origin', $this->site->getDomain());
        $response->headers->set('Access-Control-Allow-Headers', ["Origin", "X-Requested-With", "Content-Type", "Access-Control-Allow-Origin"]);
        $response->headers->set("Cache-Control", "no-cache");
        $response->headers->set('Access-Control-Allow-Methods', ["PUT", "GET", "POST", "DELETE", "OPTIONS"]);



        return $response;



    }

    /**
     * @Route("/get-reactions/{token}/{object}", name="get_reactions", requirements={"object" = ".+"})
     */
    public function getReactions(Request $request, $token, $object){

        $res = array();

        $siteObjectRepository = $this->getDoctrine()->getRepository("AppBundle:SiteObject");

        $siteObject = $siteObjectRepository->retrieveObjectByString($this->site, $object);

        if(!$siteObject){

            $response =  $this->json($res);
            $response->headers->set('Access-Control-Allow-Origin', $this->site->getDomain());
            $response->headers->set('Access-Control-Allow-Headers', ["Origin", "X-Requested-With", "Content-Type", "Access-Control-Allow-Origin"]);
            $response->headers->set("Cache-Control", "no-cache");
            $response->headers->set('Access-Control-Allow-Methods', ["PUT", "GET", "POST", "DELETE", "OPTIONS"]);


            return $response;
        }


        $reactionsRepository = $this->getDoctrine()->getRepository("AppBundle:UserReaction");

        $reactions = $reactionsRepository->getReactions($siteObject);

        if(empty($reactions)){

            $response =  $this->json($res);
            $response->headers->set('Access-Control-Allow-Origin', $this->site->getDomain());
            $response->headers->set('Access-Control-Allow-Headers', ["Origin", "X-Requested-With", "Content-Type", "Access-Control-Allow-Origin"]);
            $response->headers->set("Cache-Control", "no-cache");
            $response->headers->set('Access-Control-Allow-Methods', ["PUT", "GET", "POST", "DELETE", "OPTIONS"]);


            return $response;
        }


        foreach ($reactions as $reaction) {

            $row = array(
                "user" => array("email"=> $reaction->getUser()->getEmail()),
                "reaction" => array("stars"=> $reaction->getStars(), "comment" => $reaction->getComment() ),
                "site_object" => array("url"=> $reaction->getObject()->getUrl())
            );

            $res[] = $row;

        }

        $response =  $this->json($res);
        $response->headers->set('Access-Control-Allow-Origin', $this->site->getDomain());
        $response->headers->set('Access-Control-Allow-Headers', ["Origin", "X-Requested-With", "Content-Type", "Access-Control-Allow-Origin"]);
        $response->headers->set("Cache-Control", "no-cache");
        $response->headers->set('Access-Control-Allow-Methods', ["PUT", "GET", "POST", "DELETE", "OPTIONS"]);


        return $response;








    }






}
