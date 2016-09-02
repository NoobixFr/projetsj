<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 * 
 * @ORM\Table(name="pj_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * 
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage = "Le nom d'une catégorie doit avoir moins de {{ limit }} caractères",
     *     maxMessage = "Le nom d'une catégorie doit avoir plus de {{ limit }} caractères"
     *      )
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="categories")
     * @Assert\Valid()
     */
    private $articles;

    /**
     * Get id
     *
     * @return int
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
     * @return Category
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

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function addArticle(Article $article)
    {
         $this->articles[] = $article;
    }

    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    public function getArticles()
    {
        return $this->articles;
    }
}

