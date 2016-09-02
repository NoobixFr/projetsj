<?php

namespace BackendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;

class CommentController extends Controller
{
    /**
     * Permet de visualiser un commentaire
     * @Route("/secured/voir-commentaire/{id}", name="show_comment", requirements={
     *      "id": "\d+"
     *     })
     */
    public function showCommentAction($id){

        $entityManager = $this->getDoctrine()->getEntityManager();

        $comment = $entityManager->getRepository("AppBundle:Comment")->find($id);

        return $this->render('BackendBundle:comment:show_comment.html.twig', array(
            'comment' => $comment,
        ));
    }

    /**
     * Permet de valider un commentaire afin qu'il soit affiché
     * @Route("/secured/valider-commentaire/{id}", name="validate_comment", requirements={
     *      "id": "\d+"
     *     })
     */
    public function validateCommentAction($id){

        $entityManager = $this->getDoctrine()->getEntityManager();

        $comment = $entityManager->getRepository("AppBundle:Comment")->find($id);

        $comment->setPublished(true);
        $comment->setNew(false);

        $entityManager->flush();

        return $this->render('BackendBundle:comment:show_comment.html.twig', array(
            'comment' => $comment,
        ));
    }

    /**
     * Permet de d'invalider un commentaire afin qu'il ne soit plus affiché
     * @Route("/secured/invalider-commentaire/{id}", name="unvalidate_comment", requirements={
     *      "id": "\d+"
     *     })
     */
    public function unvalidateCommentAction($id){

        $entityManager = $this->getDoctrine()->getEntityManager();

        $comment = $entityManager->getRepository("AppBundle:Comment")->find($id);

        $comment->setPublished(false);
        $comment->setNew(false);

        $entityManager->flush();

        return $this->render('BackendBundle:comment:show_comment.html.twig', array(
            'comment' => $comment,
        ));
    }

    /**
     * Permet de supprimer un commentaire
     * @Route("/secured/supprimer-commentaire/{id}", name="delete_comment", requirements={
     *      "id": "\d+"
     *     })
     */
    public function deleteCommentAction($id){

        $entityManager = $this->getDoctrine()->getEntityManager();

        $comment = $entityManager->getRepository("AppBundle:Comment")->find($id);

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->render('BackendBundle:comment:show_comment.html.twig', array(
            'comment' => $comment,
        ));
    }

    /**
     * Affiches les nouvaux commentaires
     * @Route("/secured/nouveau-commentaire", name="show_new_comment")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showNewComment(){
        $entityManager = $this->getDoctrine()->getManager();

        $comments = $entityManager->getRepository("AppBundle:Comment")->findBy(array("new"=>true));

        return $this->render('BackendBundle:comment:show_new_comment.html.twig', array(
            'comments' => $comments,
        ));
    }
}
