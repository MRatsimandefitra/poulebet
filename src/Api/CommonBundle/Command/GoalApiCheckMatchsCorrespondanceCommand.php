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
use Api\DBBundle\Entity\MatchsCorrespondance;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiCheckMatchsCorrespondanceCommand extends ContainerAwareCommand implements InterfaceDB
{


    protected function configure()
    {
        $this
            ->setName('goalapi:insert:matchs-correspondance')
            // the short description shown while running "php bin/console list"
            ->setDescription('check matchs correspondance ).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $file = $container->get('kernel')->getRootDir().'/../web/json/matchs-frantsay.csv';
        if (($handle = fopen($file, "r")) !== FALSE) {

            while (($data = fgetcsv($handle, null, "\n")) !== FALSE) {

                foreach($data as $kData => $itemsData){

                    $arrayData = explode(";", $itemsData);
                    $entityMatchsCorres = $em->getRepository(self::ENTITY_MATCHS_CORRESPONDANT)->findOneBy(array('equipeId' => $arrayData[0]));
                    $new = false;
                    if(!$entityMatchsCorres){
                        $entityMatchsCorres = new MatchsCorrespondance();
                        $new = true;
                    }
                      $entityMatchsCorres->setEquipeId($arrayData[0]);
                      $entityMatchsCorres->setEquipeGoalApi($arrayData[1]);
                      $entityMatchsCorres->setEquipeNetbetSport($arrayData[2]);
                    if($new){
                        $em->persist($entityMatchsCorres);
                    }
                    $em->flush();
                    $output->writeln("Matchs correspondante " .$entityMatchsCorres->getEquipeGoalApi()." inserted");

                }
            }
        }

        $output->writeln("Command was successsful");
    }


}