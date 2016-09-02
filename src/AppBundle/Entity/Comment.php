<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Permet de gérer les commentaires posté sur le site
 *
 * @ORM\Table(name="pj_comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 * 
 */
class Comment
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
     * @ORM\Column(name="username", type="string", length=50)
     * @Assert\Length(
     *     min=5,
     *     max=50,
     *     minMessage="Votre nom doit contenir plus {{ limit }} caractères",
     *     maxMessage="Votre nom doit contenir moins {{ limit }} caractères"
     *     )
     */
    private $username;


    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\Email(
     *     message = "l'e-mail '{{ value }}' n'est pas valide.",
     *     checkMX = false
     * )
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message="Vous devez remplir un message")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var bool
     *
     * @ORM\Column(name="new", type="boolean")
     */
    private $new;

    /**
     * @var Article
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Article", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $article;


    public function __construct()
    {
        $this->createdAt         = new \Datetime();
        $this->published         = false;
        $this->new               = true;
    }

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
     * Set username
     *
     * @param string $username
     *
     * @return Comment
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Comment
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Comment
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set new
     *
     * @param boolean $new
     *
     * @return Comment
     */
    public function setNew($new)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * Get new
     *
     * @return bool
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }


}

