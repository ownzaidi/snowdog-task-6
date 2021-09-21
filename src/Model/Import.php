<?php

namespace Ownzaidi\Task6\Model;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Import
{
    /**
     * @var Database|\PDO
     */
    private $database;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;

    public function __construct(
        Database $database,
        UserManager $userManager,
        WebsiteManager $websiteManager,
        PageManager $pageManager
    ) {
        $this->database = $database;
        $this->userManager = $userManager;
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
    }

    public function addWebsites($websites)
    {
        try {
            // Adding a user
            $this->userManager->create('someone', 'password', 'Sample User');
            $user = $this->userManager->getByLogin('someone');

            // adding websites
            foreach ($websites as $website) {
                if (isset($website["website"]) && isset($website["name"])) {
                    $this->websiteManager->create($user, $website["name"], $website["website"]);
                }
            }

            // adding pages
            foreach ($websites as $website) {
                if (isset($website["page"]) && isset($website["refwebsite"])) {
                    $web = $this->websiteManager->getByUrl($website["refwebsite"]);
                    $pages = $this->pageManager->getByUrl($website["page"], $web);
                    if (count($pages) < 1) {
                        $this->pageManager->create($web, $website["page"]);
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}