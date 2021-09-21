<?php

namespace Snowdog\DevTest\Model;

use Ownzaidi\Task6\Core\Database;

class ImportManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getWebsiteByUrl($url) {
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM websites WHERE hostname = :hostname');
        $query->setFetchMode(\PDO::FETCH_CLASS, Website::class);
        $query->bindParam(':hostname', $url, \PDO::PARAM_STR);
        $query->execute();
        /** @var Website $website */
        $website = $query->fetch(\PDO::FETCH_CLASS);
        return $website;
    }

    public function getPagesByUrl($url, $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website AND url = :url');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->bindParam(':url', $url, \PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }
}