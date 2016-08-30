<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 18/08/2016
 * Time: 15:42
 */
namespace Api\CommonBundle\Command;

use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdminCommand extends ContainerAwareCommand
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_TEAMS = 'ApiDBBundle:Teams';


    protected function configure()
    {
        $this
            ->setName('admin:enable')
            ->addArgument('username')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check every 2 minutes the json data from goal api by ywoume ;).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getJson();
        $items = $data['items'];

        $output->writeln("Set data in database");
        $this->setDataInDatabase($items, $output);
        $output->writeln("Command was successsful");

    }


}