<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use AppBundle\Entity\Learning;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Learning", mappedBy="user", cascade={"persist"})
     */
    protected $learningList;

    public function __construct()
    {
        parent::__construct();
        $this->learningList = new ArrayCollection();
    }

    public function getLearningList()
    {
        return $this->learningList;
    }

    public function setLearningList($learningList)
    {
        $this->learningList = $learningList;

        return $this;
    }
}
