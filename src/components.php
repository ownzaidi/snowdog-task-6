<?php

use Snowdog\DevTest\Component\CommandRepository;
use Snowdog\DevTest\Component\RouteRepository;
use Ownzaidi\Task6\Controller\ImportSiteMap;
use Ownzaidi\Task6\Command\ImportSiteMapCmd;

RouteRepository::registerRoute('POST', '/importwebsite', ImportSiteMap::class, 'execute');
CommandRepository::registerCommand('import_sitemap', ImportSiteMapCmd::class);
