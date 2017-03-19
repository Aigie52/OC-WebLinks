<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 18/03/2017
 * Time: 16:50
 */

namespace WebLinks\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Form\Type\LinkType;
use WebLinks\Form\Type\UserType;

class AdminController
{
    /**
     * Admin home page controller
     * @param Application $app Silex application
     * @return mixed
     */
    public function indexAction(Application $app)
    {
        $links = $app['dao.link']->findAll();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin.html.twig', array(
            'links' => $links,
            'users' => $users,
        ));
    }

    /**
     * Edit user controller
     * @param integer $id User id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return mixed
     */
    public function editUserAction($id, Request $request, Application $app)
    {
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plainPassword = $user->getPassword();
            // Find the encoder for the user
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            // Compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Edit user',
            'userForm' => $userForm->createView()
        ));
    }

    /**
     * Delete user controller
     * @param $id
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id, Application $app)
    {
        // Delete all associated links
        $app['dao.link']->deleteAllByUser($id);
        // Delete user
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The user was successfully deleted.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Edit link controller
     * @param $id
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function editLinkAction($id, Request $request, Application $app)
    {
        $link = $app['dao.link']->find($id);
        $linkForm = $app['form.factory']->create(LinkType::class, $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $app['dao.link']->save($link);
            $app['session']->getFlashBag()->add('success', 'The link was successfully updated.');
        }
        return $app['twig']->render('link_form.html.twig', array(
            'title' => 'Edit link',
            'linkForm' => $linkForm->createView()
        ));
    }

    public function deleteLinkAction($id, Application $app)
    {
        // Delete the link
        $app['dao.link']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The link was successfully removed.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
}
