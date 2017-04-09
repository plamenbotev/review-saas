<?php
/**
 * Created by PhpStorm.
 * User: botev
 * Date: 09.04.17
 * Time: 15:37
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Site;

interface TokenAuthenticatedController
{

    public function setCurrentSite(Site $site);

}