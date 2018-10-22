<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GitProject;
use App\Form\Type\GitProjectType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("git-project")
 * @FOSRest\NamePrefix(value="api_v1_git_project_")
 */
class GitProjectController extends FOSRestController
{
    /**
     * Get all projects.
     *
     * @return object
     */
    public function cgetAction()
    {
        //$offers = $this->getDoctrine()->getManager()->getRepository(GitProject::class)->findAll();
        $em = $this->getDoctrine()->getManager();

        $user = null;

        if ($this->getUser()) {
            $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
        }

        $offers = $this->getDoctrine()->getManager()->getRepository(GitProject::class)->findAllWithPersonalValue($user);

        return $offers;
    }

    /**
     * Get single user by id.
     *
     * @param GitProject $gitProject
     *
     * @return null|object
     */
    public function getAction(GitProject $gitProject)
    {
        return $gitProject;
    }

    /**
     * Create a new gitProject entry.
     *
     *
     * @param Request $request
     *
     * @return GitProject|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gitProject = new gitProject();
        $form = $this->createForm(GitProjectType::class, $gitProject);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($gitProject);
        $em->flush();

        return $gitProject;
    }

    /**
     * Create a new gitProject entry.
     *
     *
     * @param GitProject $gitProject
     * @param Request    $request
     *
     * @return GitProject|\Symfony\Component\HttpFoundation\Response
     */
    public function putAction(GitProject $gitProject, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(GitProjectType::class, $gitProject);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($gitProject);
        $em->flush();

        return $gitProject;
    }

    public function deleteAction(GitProject $gitProject)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($gitProject);
        $em->flush();

        return ['success' => true];
    }
}
