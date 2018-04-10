<?php


namespace FD\mediadminBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="check1", type="boolean")
     */
    private $check1 = false;

    /**
     * @ORM\Column(name="check2", type="boolean")
     */
    private $check2 = false;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }



    /**
     * Set check1.
     *
     * @param bool $check1
     *
     * @return User
     */
    public function setCheck1($check1)
    {
        $this->check1 = $check1;

        return $this;
    }

    /**
     * Get check1.
     *
     * @return bool
     */
    public function getCheck1()
    {
        return $this->check1;
    }

    /**
     * Set check2.
     *
     * @param bool $check2
     *
     * @return User
     */
    public function setCheck2($check2)
    {
        $this->check2 = $check2;

        return $this;
    }

    /**
     * Get check2.
     *
     * @return bool
     */
    public function getCheck2()
    {
        return $this->check2;
    }
}
