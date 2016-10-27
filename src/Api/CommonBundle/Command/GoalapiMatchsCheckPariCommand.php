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

class GoalapiMatchsCheckPariCommand extends ContainerAwareCommand implements InterfaceDB{
    protected function configure()
    {
        $this
            ->setName('goalapi:check:pari')
            // the short description shown while running "php bin/console list"
            ->setDescription('check matchs with no pari ;) ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $matchs = $em->getRepository(self::ENTITY_MATCHS)->findBy(array('statusMatch' => 'not_started'));
        if(is_array($matchs) && !empty($matchs) && count($matchs) > 0 ){
            foreach($matchs as $kMatchs => $itemsMatchs){
                $datePari = $itemsMatchs->getDateMatch()->sub(new \DateInterval('PT5M'));
                $dateMatchs  = $itemsMatchs->getDateMatch();
                if($dateMatchs == $datePari){
                    $itemsMatchs->setIsNoPari(true);
                }
                $em->flush();
                $output->writeln("Insertion no pari " .$itemsMatchs->getId());
            }
        }else{

        }
    }
}