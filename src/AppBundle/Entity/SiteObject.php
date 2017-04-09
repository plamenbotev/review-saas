<?php
/**
 * Created by PhpStorm.
 * User: botev
 * Date: 09.04.17
 * Time: 13:35
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * @ORM\Entity
 * @ORM\Table(name="site_object",
 *     indexes={
 *         @Index(name="hash", columns={"hash"})
 *      },
 *     uniqueConstraints={@UniqueConstraint(name="site_object", columns={"site", "hash"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SiteObjectRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SiteObject
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var $id Integer
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $hash;

    /**
     * @ORM\ManyToOne(targetEntity="Site", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     */
    private $site;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set site
     *
     * @param \AppBundle\Entity\Site $site
     *
     * @return SiteObject
     */
    public function setSite(\AppBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \AppBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->hash = md5($this->url);

    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->hash = md5($this->url);
    }
}
