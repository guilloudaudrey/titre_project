<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace AppBundle\Controller;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
/**
 * Controller managing the user profile.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends Controller
{
    private $eventDispatcher;
    private $formFactory;
    private $userManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, UserManagerInterface $userManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
    }

    /**
     * Show the user.
     */
    public function showAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $postslist = $user->getPosts();
        $postResponsesList = $user->getPostResponses();
        $responsesToPostList = [];
        $em = $this->getDoctrine()->getManager();
        $postsActive = $em->getRepository('AppBundle:Post')->findBy(array(
            'status' => 'active',
            'user' => $user->getId()));




        foreach($postslist as $post){
            $responsesToPostList[] = $post->getPostResponses();
        }


        $responsesToPost  = $this->get('knp_paginator')->paginate(
            $responsesToPostList,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );


        $posts  = $this->get('knp_paginator')->paginate(
            $postslist,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );

        $postResponses = $this->get('knp_paginator')->paginate(
            $postResponsesList,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'posts' => $posts,
            'postResponses' => $postResponses,
            'reponsesToPost' => $responsesToPost,
            'postsActive' => $postsActive
        ));
    }
}