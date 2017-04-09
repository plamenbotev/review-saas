<?php
/**
 * Created by PhpStorm.
 * User: botev
 * Date: 09.04.17
 * Time: 14:03
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_reaction", indexes={
 *    @Index(name="object", columns={"object"} ),
 *    @Index(name="user", columns={"user"} ),
 *    @Index(name="stars", columns={"stars"} )
 * },
 * uniqueConstraints={@UniqueConstraint(name="user_object", columns={"user", "object"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserReactionRepository")
 */
class UserReaction
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var $id Integer
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="SiteObject")
     * @ORM\JoinColumn(name="object", referencedColumnName="id", onDelete="CASCADE")
     */
    private $object;


    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min="1", max="5")
     * @ORM\Column(type="integer")
     */
    private $stars;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $comment;



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
     * Set stars
     *
     * @param integer $stars
     *
     * @return UserReaction
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return integer
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return UserReaction
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set object
     *
     * @param \AppBundle\Entity\SiteObject $object
     *
     * @return UserReaction
     */
    public function setObject(\AppBundle\Entity\SiteObject $object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return \AppBundle\Entity\SiteObject
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserReaction
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
