<?php
// src/AppBundle/Controller/ArticleController

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController  extends Controller
{

    /**
     * Affiche la page d'accueil du site qui contient la liste des articles.
     * @Route("/", name="home")
     */
    public function showArticlesAction()
    {
        $articles =  $this->getDoctrine()->getManager()->getRepository("AppBundle:Article")->findBy(array('published' => true),array('createdAt' => 'desc'));
        return $this->render(':article:show_articles.html.twig', array('articles_list' => $articles));
    }

    /**
     * Affiche un article particulier avec le formulaire pour ajouter des commentaires et la liste des commentaires associés
     * @Route("/article/{id}", name="show_article", requirements={
     *      "id": "\d+"
     *     })
     */
    public function showArticleAction($id, Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        // On récupère l'article via son id
        $article = $entityManager->getRepository("AppBundle:Article")->find($id);

        if($article->isPublished() == true){
            $comments = $entityManager->getRepository("AppBundle:Comment")->findBy(array("article"=>$id, "published"=>1),array('createdAt' => 'desc'));

            // on se sert de l'objet "Comment" pour créer le formulaire associé.
            $comment = new Comment();
            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $comment);
            $formBuilder
                ->add('username',  TextType::class)
                ->add('mail',      EmailType::class)
                ->add('content',   TextareaType::class)
                ->add('save',      SubmitType::class, array('label' => 'Poster'));
            $form = $formBuilder->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
                $comment->setArticle($article);
                $comment->setPublished(false);
                $comment->setNew(true);
                $entityManager->persist($comment);
                $entityManager->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Commentaire bien enregistré.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirect($this->generateUrl('show_article', array('id' => $article->getId())));
            }

            return $this->render(':article:show_article.html.twig', array('article'=>$article, 'comments' => $comments, 'form' => $form->createView()));
        }else{
            throw new NotFoundHttpException("Article introuvable !");
        }

    }
    
}