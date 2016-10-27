<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 18/08/2016
 * Time: 15:42
 */
namespace Api\CommonBundle\Command;

use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalapiMatchsGagnerCommand extends ContainerAwareCommand implements InterfaceDB
{

    protected function configure()
    {
        $this
            ->setName('goalapi:check:isgagne')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check matchs is gagner by ywoume ;) ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
       $matchs =  $em->getRepository(self::ENTITY_MATCHS)->findMatchsFinished();
        if(is_array($matchs) && count($matchs) > 0 ){
            foreach($matchs as $kMatchs => $itemsMatchs){
                $statusMatchs = $itemsMatchs->getStatusMatch();
                if($statusMatchs ==='finished'){
                    $scoreDomicile = substr($itemsMatchs->getScore(), 0, 1);
                    $scoreVisiteur = substr($itemsMatchs->getScore(), -1, 1);
                    if($scoreDomicile === $scoreVisiteur){

                    }
                    if($scoreDomicile > $scoreDomicile){

                    }
                }
            }
        }
        $output->writeln("Command was successsful");

    }


}