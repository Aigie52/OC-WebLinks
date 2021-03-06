<?php

namespace WebLinks\DAO;

use WebLinks\Domain\Link;

class LinkDAO extends DAO 
{
    /**
     * @var UserDAO
     */
    private $userDAO;

    /**
     * @param UserDAO $userDAO
     */
    public function setUserDAO (UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    /**
     * Returns a list of all links, sorted by id.
     *
     * @return array A list of all links.
     */
    public function findAll() {
        $sql = "select * from t_link order by link_id desc";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['link_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Returns a link matching the supplied id
     * @param $id
     * @return Link
     * @throws \Exception
     */
    public function find($id)
    {
        $sql = "select * from t_link where link_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No link matching id " . $id);
    }

    /**
     * Saves a link into the database
     * @param Link $link
     */
    public function save(Link $link)
    {
        $linkData = array(
            'link_title' => $link->getTitle(),
            'link_url' => $link->getUrl(),
            'user_id' => $link->getAuthor()->getId()
        );

        if ($link->getId()) {
            // The link has already been saved : update it
            $this->getDb()->update('t_link', $linkData, array(
                'link_id' => $link->getId()
            ));
        } else {
            // The link has never been saved : insrt it
            $this->getDb()->insert('t_link', $linkData);
            // Get the id of the newly created link and set it on the entity
            $id = $this->getDb()->lastInsertId();
            $link->setId($id);
        }
    }


    /**
     * Remove a link form the database
     * @param $id
     */
    public function delete($id)
    {
        $this->getDb()->delete('t_link', array('link_id' => $id));
    }

    /**
     * Removes all comments for a user
     * @param $userId
     */
    public function deleteAllByUser($userId)
    {
        $this->getDb()->delete('t_link', array('user_id' => $userId));
    }

    /**
     * Creates an Link object based on a DB row.
     *
     * @param array $row The DB row containing Link data.
     * @return \WebLinks\Domain\Link
     */
    protected function buildDomainObject(array $row) {
        $link = new Link();
        $link->setId($row['link_id']);
        $link->setTitle($row['link_title']);
        $link->setUrl($row['link_url']);

        if (array_key_exists('user_id', $row)) {
            $userId = $row['user_id'];
            $user = $this->userDAO->find($userId);
            $link->setAuthor($user);
        }
        
        return $link;
    }
}
