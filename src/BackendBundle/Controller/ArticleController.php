<?php
// src/BackendBundle/Controller/ArticleController

namespace BackendBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Article;

use AppBundle\Entity\Category;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{

    /**
     * Affiche la page contenant le formulaire de création d'article
     * @Route("/secured/creer-article", name="create_article")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createArticleAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article =  new Article();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $article);

        $formBuilder
            ->add('title',     TextType::class)
            ->add('content',   CKEditorType::class)
            ->add('published', CheckboxType::class, array(
                'label' => 'publier',
                'required' => false,
                'label_attr' => array(
                    'class' => 'checkbox-inline'
                )
            ))
            ->add('categories', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label_attr' => array(
                    'class' => 'checkbox-inline'
                )
            ))
            ->add('save',      SubmitType::class);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($this->getUser()->getUsername());
            $entityManager->persist($article);
            $entityManager->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Article bien enregistré.');

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('backend_dashbord'));
        }

        return $this->render('BackendBundle:article:create_article.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Affiche les détails d'un article
     * @route("/secured/article/{id}", name="backend_show_article", requirements={
     *              "id": "\d+"
     *        })
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showArticleAction($id, Request $request){

        // on cherche l'article à modifier
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository("AppBundle:Article")->find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // on construit le formulaire avec l'article récupéré
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $article);

        $formBuilder
            ->add('title',     TextType::class)
            ->add('content',   CKEditorType::class)
            ->add('published', CheckboxType::class, array(
                'label' => 'publier',
                'required' => false,
                'label_attr' => array(
                    'class' => 'checkbox-inline'
                )
            ))
            ->add('categories', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label_attr' => array(
                    'class' => 'checkbox-inline'
                )
            ))
            ->add('save',      SubmitType::class);

        $form = $formBuilder->getForm();

        if ($form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $entityManager->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Article bien modifiée.');

            return $this->redirect($this->generateUrl('backend_show_article', array('id' => $article->getId())));
        }

        return $this->render('BackendBundle:article:update_article.html.twig', array(
            'form'   => $form->createView(),
            'article' => $article // Je passe également l'annonce à la vue si jamais elle veut l'afficher
        ));
    }

    /**
     * Supprime un article.
     * @Route("/secured/supprimer-article/{id}", name="backend_delete_article", requirements={
     *              "id": "\d+"
     *        })
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArticleAction($id, Request $request){
        // on cherche l'article à modifier
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository("AppBundle:Article")->find($id);

        if (null === $article) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Article bien supprimé.');

        return $this->redirect($this->generateUrl('backend_show_articles'));
    }

    /**
     * Affiche la page d'accueil du site qui contient la liste des articles.
     * @Route("/secured/liste-article", name="backend_show_articles")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showArticlesListAction(){
        // Récupération de l'entity manager pour interagir avec la base de données
        $entityManager = $this->getDoctrine()->getManager();

        // On récupére la liste de tout les articles.
        $articles = $entityManager->getRepository("AppBundle:Article")->findAll();

        // On apelle la vue en lui passant la liste des articles
        return $this->render('BackendBundle:article:show_articles.html.twig', array('articles'=>$articles));
    }

    /**
     * Change l'état d'un article (publié/non-publié)
     * @route("/secured/changer-etat-publication", name="change_state_published")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function changeStatePublishedAction(Request $request){

        // On recupere l'entity manager
        $entityManger = $this->getDoctrine()->getManager();

        //On recupere l'article à modifier grâce à son id
        $article = $entityManger->getRepository("AppBundle:Article")->find($request->request->get('id'));

        //On change l'etat de publication de l'article
        $article->togglePublished();
        $entityManger->flush();
        $state = array("state" => $article->isPublished());
        return new JsonResponse($state);
    }

    /**
     * Permet d'ajouter des catégories d'article.
     * @route("/secured/creer-categorie", name="backend_create_categories")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function createCategoriesAction(Request $request){
        // on recupere le titre de l'article.
        $name = $request->request->get('name');

        $category = new Category();
        $category->setName($name);



        $validator = $this->get('validator');
        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            $response = array("errors" => $errors[0]->getMessage());
            return new JsonResponse($response, 409);
        }else{

            // notre categorie est valide on peut l'enregistrer
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($category);
            $entityManger->flush();

            $response = array("message" => "la catégorie à bien été créée.");
            return new JsonResponse($response);
        }

    }

    /**
     * Permet d'afficher la liste des catégories pour les articles
     * @route("/secured/liste-categories", name="show_categories")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showCategoriesAction(){
        // Récupération de l'entity manager pour interagir avec la base de données
        $entityManager = $this->getDoctrine()->getManager();

        // On récupére la liste de tout les articles.
        $categories = $entityManager->getRepository("AppBundle:Category")->findAll();

        // On apelle la vue en lui passant la liste des articles
        return $this->render('BackendBundle:article:show_categories.html.twig', array('categories'=>$categories));
    }

    /**
     * Permet la suppresion d'une catégorie d'article
     * @route("/secured/supprimer-categorie", name="delete_category")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCategoryAction(Request $request){
        // On recupere l'entity manager
        $entityManger = $this->getDoctrine()->getManager();

        //On recupere l'article à modifier grâce à son id
        $category = $entityManger->getRepository("AppBundle:Category")->find($request->request->get('id'));

        $entityManger->remove($category);
        $entityManger->flush();

        $response = array("message" => "la catégorie à bien été supprimé.");
        return new JsonResponse($response);
    }


}