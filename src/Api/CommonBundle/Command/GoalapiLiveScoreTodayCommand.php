<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 05/09/2016
 * Time: 11:19
 */

namespace Api\CommonBundle\Command;


use Api\DBBundle\Entity\Matchs;
use Api\DBBundle\Entity\MatchsEvent;
use Api\DBBundle\Entity\Teams;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalapiLiveScoreTodayCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('goalapi:check:livescore-today')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check match Live');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $allChampionat = $em->getRepository('ApiDBBundle:Championat')->findAll();
        $this->getUrlNow('test');
        if (is_array($allChampionat) && count($allChampionat) > 0) {
            foreach ($allChampionat as $kAllChampionat => $itemsAllChampionat) {

            }
        }
        $output->writeln("Command was successfull");

    }

    private function getUrlNow($championat)
    {
        $em = $this->getEm();
        $now = new \DateTime('now');
        $tmpNow = $now->format('Y-m-d');
        $dateDebutString = $tmpNow. ' 00:00:00';
        $dateFinaleString = $tmpNow. ' 23:59:59';
        $dateDebut = new \DateTime($dateFinaleString);
        $dateDebutTimestamp  = $dateDebut->getTimestamp();
        $dateFinale = new \DateTime($dateFinaleString);
        $dateFinaleTimestamp = $dateFinale->getTimestamp();

        $apiKey = $em->getRepository('ApiDBBundle:Mention')->findAll();
        foreach ($apiKey as $vApiKey) {
            $apiKey = $vApiKey->getApiKeyGoalapi();
        }
        //$url = "http://api.xmlscores.com/matches/?c[]=" . $data->getNomChampionat() . "&f=json&e=1&l=128&b=today&open=" . $apiKey;
        //   var_dump($url); die;
        //$url = $this->getContainer()->get('kernel')->getRootDir().'/../web/json/live/engcslive1.json';
        // var_dump($url); die;
        //var_dump($url); die;
      /*  $content = file_get_contents($url);
        $arrayJson = json_decode($content, true);

        return $arrayJson;*/
    }


    private function getEm()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        return $em;
    }

    private function sendErrorEmail($body)
    {
        $em = $this->getEm();
        $wm = $this->getContainer()->get('mail.manager');
        $mailadmin = $em->getRepository(self::ENTITY_ADMIN)->findBy(array('roles' => array('SUPER_ADMIN')));
        foreach ($mailadmin as $vMailAdmin) {
            $wm->setSubject("Error of treatement");
            $wm->setFrom($vMailAdmin->getEmail());
            $wm->setTo($vMailAdmin->getEmail());
            $wm->setBody($body);
            $wm->send();
        }

    }

    private function getMessagePush($vEventItems, $matchs)
    {
        /*$msg = " <img src='". "http:dplb.arkeup.com/".$matchs->getCheminLogoDomicile() ."'width='15' height='15' /> <b>". $matchs->getEquipeDomicile()->getFullNameClub() ."</b> VS  <img src='".$matchs->getCheminLogoVisiteur()."' width='15' height='15' />  <b> ". $matchs->getEquipeVisiteur()->getFullNameClub() ."</b> <br />";*/
        $msg = "" . $matchs->getEquipeDomicile() . ' VS ' . $matchs->getEquipeVisiteur() . "$";
        $msg .= "But de " . $vEventItems['player'] . " à la " . $vEventItems['minute'] . "$";
        $msg .= "Score " . $vEventItems['score'];

        $msg2 = "But de " . $vEventItems['player'] . " à la " . $vEventItems['minute'] . " $ - " . $matchs->getEquipeDomicile() . ' VS ' . $matchs->getEquipeVisiteur() . " \n " . " Score " . $vEventItems['score'];
        return $msg;
    }

    private function sendNotif($matchs, $vEventItems, $output)
    {
        $em = $this->getEm();
        if (array_key_exists('score', $vEventItems)) {
            if ($matchs->getScore() != $vEventItems['score']) {
                // Si score différent alors push notification
                $output->writeln("A notification will be sent");
                $connected = $em->getRepository('ApiDBBundle:Connected')->findAll();
                $device_token = array();
                foreach ($connected as $connectedItems) {
                    $dqlVote = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                                                    LEFT JOIN vu.matchs as m
                                                    LEFT JOIN vu.utilisateur as u
                                                    WHERE u.email = :email
                                                    AND m.id = :matchId
                                                    AND vu.vote IS NOT NULL";
                    $query = $em->createQuery($dqlVote);
                    /*$query->setParameter('email', $connectedItems->getUsername());*/
                    $query->setParameters(array(
                        'email' => $connectedItems->getUsername(),
                        'matchId' => $matchs->getId()
                    ));
                    $result = $query->getResult();
                    if ($result) {
                        if (!in_array($connectedItems->getDevice(), $device_token)) {
                            $device_token[] = $connectedItems->getDevice();
                        }
                    }
                    /*  $devices = $connectedItems->getDevice();
                      foreach ($devices as $device) {
                          //$device_token[] = $device->getToken();
                          array_push($device_token, $device->getToken());
                      }*/
                }
            }

            $messageData = array(
                /*    "message" => $vEventItems['player'] . " a marqué un but à la " . $vEventItems['minute'] . "° minute. Score:" . $vEventItems['score'],*/
                "message" => $this->getMessagePush($vEventItems, $matchs),
                "type" => "livescore"
            );
            $data = array(
                'registration_ids' => $device_token,
                'data' => $messageData
            );
            $http = $this->getContainer()->get('http');
            $res = $http->sendGCMNotification($data);

            $output->writeln($res);
        }
    }
}