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

class GoalapiMatchsCheckPariCommand extends ContainerAwareCommand implements InterfaceDB
{
    protected function configure()
    {
        $this
            ->setName('goalapi:check:pari')
            // the short description shown while running "php bin/console list"
            ->setDescription('check matchs with no pari ;) ');
    }
// A NE PLUS UTILISER
//    protected function execute(InputInterface $input, OutputInterface $output)
//    {
//        $container = $this->getContainer();
//        $em = $container->get('doctrine.orm.entity_manager');
//
//        #$matchs = $em->getRepository(self::ENTITY_MATCHS)->findBy(array('statusMatch' => 'not_started'));
//        $matchs = $em->getRepository(self::ENTITY_MATCHS)->findMatchsStartedToday();
//        if (is_array($matchs) && !empty($matchs) && count($matchs) > 0) {
//            foreach ($matchs as $kMatchs => $itemsMatchs) {
//              /*  $datePari = $itemsMatchs->getDateMatch()->sub(new \DateInterval('PT5M'));
//                $dateMatchs = $itemsMatchs->getDateMatch();
//                $now = new \DateTime('now');
//                var_dump($dateMatchs->add()); die;*/
//                $dateMatchs = $itemsMatchs->getDateMatch();
//               // var_dump($dateMatchs); die;
//                $now = new \DateTime('now');
//             //   var_dump($dateMatchs); die;
//                if ($now >= $dateMatchs->sub(new \DateInterval('PT5M')) /* && $now <= $dateMatchs->add(new \DateInterval('PT5M'))*/) {
//                    $itemsMatchs->setIsNoPari(true);
//                    $em->flush();
//
//                    $output->writeln("Insertion no pari " . $itemsMatchs->getId());
//                    // notification no pari
//                    $users = $em->getRepository(self::ENTITY_UTILISATEUR)->findAll();
//                    $device_token = array();
//                    foreach ($users as $user) {
//                        $devices = $user->getDevices();
//                        foreach ($devices as $device) {
//                            $device_token[] = $device->getToken();
//                            array_push($device_token, $device->getToken());
//                        }
//                    }
//                    $messageData = array(
//                        "message" => "une mise a jour du liste des concours est requise",
//                        "type" => "concours",
//                        "categorie" => "nopari"
//                    );
//                    $data = array(
//                        'registration_ids' => $device_token,
//                        'data' => $messageData
//                    );
//                    $http = $this->getContainer()->get('http');
//                    $res = $http->sendGCMNotification($data);
//                    $output->writeln($res);
//                    $output->writeln("Notificaton is sended");
//
//                }
//            }
//
//        } else {
//            $output->writeln("Aucun matchs à parier");
//        }
//
//        #$matchs = $em->getRepository(self::ENTITY_MATCHS)->findBy(array('statusMatch' => 'finished'));
//
//        $matchs = $em->getRepository(self::ENTITY_MATCHS)->findDifferentNotStared();
//        if (is_array($matchs) && !empty($matchs) && count($matchs) > 0) {
//            foreach($matchs as $itemsMatchs){
//                $itemsMatchs->setIsNoPari(true);
//                $em->flush();
//                $output->writeln("No pari for matchs ".$itemsMatchs->getId()." status No PARI TRUE" );
//            }
//        }
//
//        //$matchs = $em->getRepository(self::ENTITY_MATCHS)->findMatchsNotRe
//        $output->writeln("command was finised");
//
//    }

}

