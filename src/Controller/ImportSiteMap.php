<?php

namespace Ownzaidi\Task6\Controller;

use Ownzaidi\Task6\Model\Import;

class ImportSiteMap
{
    /**
     * @var Import
     */
    private $importWebsites;

    public function __construct(Import $importWebsites)
    {
        $this->importWebsites = $importWebsites;
    }

    public function execute()
    {
        try {
            if (!isset($_FILES["sitemapFile"]["tmp_name"])) {
                $_SESSION['flash'] = 'Please select file to import!';
            }
            $xmlfile = file_get_contents($_FILES["sitemapFile"]["tmp_name"]);
            $data = simplexml_load_string($xmlfile);
            $websites = json_decode(json_encode($data), true);
            $websites = end($websites);
            $response = $this->importWebsites->addWebsites($websites);
            if ($response == true) {
                $_SESSION['flash'] = 'Website and pages are imported successfully!';
            } else {
                $_SESSION['flash'] = 'Something went wrong: ' . $response;
            }
        } catch (\Exception $e) {
            $_SESSION['flash'] = 'Something went wrong: ' . $e->getMessage();
        }
        header('Location: /');
    }
}