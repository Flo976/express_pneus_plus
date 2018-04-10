<?php
namespace FD\mediadminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Tag
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\ManyToMany(targetEntity="Theme", mappedBy="tags")
     */
    private $voitures;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->voitures = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add voiture
     *
     * @param \FD\mediadminBundle\Entity\Theme $voiture
     *
     * @return Tag
     */
    public function addVoiture(\FD\mediadminBundle\Entity\Theme $voiture)
    {
        $this->voitures[] = $voiture;

        return $this;
    }

    /**
     * Remove voiture
     *
     * @param \FD\mediadminBundle\Entity\Theme $voiture
     */
    public function removeVoiture(\FD\mediadminBundle\Entity\Theme $voiture)
    {
        $this->voitures->removeElement($voiture);
    }

    /**
     * Get voitures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoitures()
    {
        return $this->voitures;
    }
}
