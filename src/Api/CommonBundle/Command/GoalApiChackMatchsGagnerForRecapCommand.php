<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 05/09/2016
 * Time: 11:19
 */

namespace Api\CommonBundle\Command;


use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Matchs;
use Api\DBBundle\Entity\MatchsEvent;
use Api\DBBundle\Entity\Teams;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiChackMatchsGagnerForRecapCommand extends ContainerAwareCommand implements InterfaceDB
{

    protected function configure()
    {
        $this
            ->setName('goalapi:check:matchs-gagner-recap')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check match gagné pour la récapitulation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $matchsVoter = $em->getRepository(self::ENTITY_MATCHS)->findMatchsForRecap();
        var_dump($matchsVoter); die;
        if($matchsVoter){
            foreach($matchsVoter as $k => $itemsMatchsVoter){
                var_dump($itemsMatchsVoter); die;
            }
        }
    }


}