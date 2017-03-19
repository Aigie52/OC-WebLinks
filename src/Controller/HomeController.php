<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 18/03/2017
 * Time: 16:16
 */

namespace WebLinks\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;

class HomeController
{
    /**
     * Home page controller
     * @param Application $app
     * @return mixed
     */
    public function indexAction(Application $app)
    {
        $links = $app['dao.link']->findAll();
        return $app['twig']->render('index.html.twig', array(
            'links' => $links
        ));
    }

    /**
     * User login controller
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function loginAction(Request $request, Application $app)
    {
        return $app['twig']->render('login.html.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }

    /**
     * Link add action
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function addLinkAction(Request $request, Application $app)
    {
        $user = $app['user'];
        $link = new Link();
        $link->setAuthor($user);
        $linkForm = $app['form.factory']->create(LinkType::class, $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $app['dao.link']->save($link);
            $app['session']->getFlashBag()->add('success', 'The link was successfully added.');
        }
        return $app['twig']->render('link_form.html.twig', array(
            'title' => 'New link',
            'linkForm' => $linkForm->createView()
        ));
    }
}
