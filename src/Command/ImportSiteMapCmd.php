<?php

namespace Ownzaidi\Task6\Command;

use Snowdog\DevTest\Core\Database;
use Ownzaidi\Task6\Model\Import;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ImportSiteMapCmd
{

    /**
     * @var Import
     */
    private $importWebsites;
    /**
     * @var QuestionHelper
     */
    private $helper;
    /**
     * @var Database
     */
    private $database;

    public function __construct(Import $importWebsites, QuestionHelper $helper, Database $database)
    {
        $this->importWebsites = $importWebsites;
        $this->helper = $helper;
    }

    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting import</info>');
        $output->writeln('<comment>Do check the XML file format to avoid any error.</comment>');
        $question = new Question('Are you sure to import websites: <comment>yes/no</comment> ', 'no');
        $start = $this->helper->ask($input, $output, $question);

        try {
            if ($start == "yes") {
                $askForFilePath = new Question('Give file path to import data from: <comment>e.g: web/sitemap/sample.xml</comment> ');
                $path = $this->helper->ask($input, $output, $askForFilePath);
                $output->writeln('<info>Reading file at path: </info><comment>'.$path.'</comment>');
                $xmlfile = file_get_contents($path);
                if ($xmlfile == false) {
                    $output->writeln('<error>Unable to load file or file not exist.</error>');
                    return;
                }
                $data = simplexml_load_string($xmlfile);
                $websites = json_decode(json_encode($data), true);
                $websites = end($websites);
                $response = $this->importWebsites->addWebsites($websites);
                if ($response == true) {
                    $output->writeln('<info>Website and pages are imported successfully!</info>');
                } else {
                    $output->writeln('<error>Something went wrong: ' . $response . '</error>');
                }
            }
        } catch (\Exception $e) {
            $output->writeln('<error>Something went wrong: ' . $e->getMessage() . '</error>');
        }
    }
}