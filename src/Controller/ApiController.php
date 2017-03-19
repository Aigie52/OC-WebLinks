<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 18/03/2017
 * Time: 16:51
 */

namespace WebLinks\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use WebLinks\Domain\Link;

class ApiController
{
    /**
     * API links controller
     * @param Application $app
     * @return JsonResponse All links in JSON format
     */
    public function getLinksAction(Application $app)
    {
        $links = $app['dao.link']->findAll();
        // Convert an array of objects ($links) into an array of associative arrays ($responseData)
        $responseData = array();
        foreach ($links as $link) {
            $responseData[] = $this->buildLinkArray($link);
        }
        // Create and return a JSON response
        return $app->json($responseData);
    }

    /**
     * API link details controller
     * @param $id
     * @param Application $app
     * @return JsonResponse Link details in JSON format
     */
    public function getLinkAction($id, Application $app)
    {
        $link = $app['dao.link']->find($id);
        $responseData = $this->buildLinkArray($link);
        // Create and return a JSON response
        return $app->json($responseData);
    }

    /**
     * Convert a link object into an associative array for JSON encoding
     * @param Link $link
     * @return array
     */
    private function buildLinkArray(Link $link)
    {
        $data = array(
            'id' => $link->getId(),
            'title' => $link->getTitle(),
            'url' => $link->getUrl(),
            'author' => array(
                'id' => $link->getAuthor()->getId(),
                'name' => $link->getAuthor()->getUsername()
            )
        );
        return $data;
    }
}
