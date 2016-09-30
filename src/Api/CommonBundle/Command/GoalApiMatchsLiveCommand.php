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

class GoalApiMatchsLiveCommand extends ContainerAwareCommand
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_TEAMS = 'ApiDBBundle:Teams';
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_ADMIN = 'ApiDBBundle:Admin';
    const ENTITY_MATCH_EVENT = 'ApiDBBundle:MatchsEvent';
    const ENTITY_DEVICE = 'ApiDBBundle:Device';

    protected function configure()
    {
        $this
            ->setName('goalapi:check:matchs-live')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check match Live');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $championat = $em->getRepository(self::ENTITY_CHAMPIONAT)->findBy(array('isEnable' => true));
        $count = 0;

        foreach ($championat as $vChampionat) {

            $count = $count + 1;
            $output->writeln(' ---  championat --- ' . $vChampionat->getFullNameChampionat());
            $data = $this->getUrlByChampionat($vChampionat->getId());
            $output->writeln("we found " . count($data['items']) . " matchs for " . $vChampionat->getFullNameChampionat());

            if ($data) {
                $count = 0;
                foreach ($data['items'] as $vItems) {
                    $count = $count + 1;

                    $mDate = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', $vItems['timestamp_starts']));
                    /*$dateDebut = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d 00:00'));*/
                    $dateDebut = \DateTime::createFromFormat('Y-m-d H:i', date('2016-09-15 00:00'));
                    $dataEnd = \DateTime::createFromFormat('Y-m-d H:i', date('2016-09-15 H:i', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'))));

                    if ($vItems['status'] === 'active') {
                        $output->writeln("Treatement ->  Matchs With ID :" . $vItems['id']);

                        $matchs = $em->getRepository(self::ENTITY_MATCH)->find($vItems['id']);
                        if (!$matchs) {
                            $matchs = new Matchs();
                        }
                        if (array_key_exists('timestamp_created', $data)) {
                            $dateCheckGoalapi = new \DateTime(strtotime($data['timestamp_created']));

                        }
                        if (array_key_exists('timestamp_created', $data)) {
                            $matchs->setTimestampCheckGoalApi($data['timestamp_created']);
                        }
                        $matchs->setDateCheckGoalApi($dateCheckGoalapi);
                        $matchs->setStateGoalApi(false);
                        $matchs->setId($vItems['id']);
                        $matchs->setStatusMatch($vItems['status']);

                        $matchs->setDateMatch($mDate);
                        // teams visiteur
                        $teamsVisiteur = $em->getRepository(self::ENTITY_TEAMS)->findOneBy(array('idNameClub' => $vItems['teams']['guests']['id']));
                        if (!$teamsVisiteur) {
                            $output->writeln('Ajout d\'un nouveau club');
                            $teamsVisiteur = new Teams();
                            $teamsVisiteur->setIdNameClub($vItems['teams']['guests']['id']);
                            $teamsVisiteur->setFullNameClub($vItems['teams']['guests']['fullname']);
                            $teamsVisiteur->setNomClub($vItems['teams']['guests']['name']);
                            $teamsVisiteur->setLogo($vItems['teams']['guests']['id']);
                            $em->persist($teamsVisiteur);
                            $em->flush();
                        }
                        $matchs->setTeamsVisiteur($teamsVisiteur);
                        $matchs->setEquipeVisiteur($teamsVisiteur->getFullNameClub());
                        // teams domicile

                        $teamsDomicile = $em->getRepository(self::ENTITY_TEAMS)->findOneBy(array('idNameClub' => $vItems['teams']['hosts']['id']));
                        if (!$teamsDomicile) {
                            $output->writeln('Ajout d\'un nouveau club');
                            $teamsDomicile = new Teams();
                            $teamsDomicile->setIdNameClub($vItems['teams']['hosts']['id']);
                            $teamsDomicile->setFullNameClub($vItems['teams']['hosts']['fullname']);
                            $teamsDomicile->setNomClub($vItems['teams']['hosts']['name']);
                            $teamsDomicile->setLogo($vItems['teams']['hosts']['id']);
                            $em->persist($teamsDomicile);
                            $em->flush();
                        }

                        $matchs->setTeamsDomicile($teamsDomicile);
                        $matchs->setEquipeDomicile($teamsDomicile->getFullNameClub());

                        $matchs->setCheminLogoVisiteur($teamsVisiteur->getIdNameClub());
                        $matchs->setCheminLogoDomicile($teamsDomicile->getIdNameClub());

                        // score
                        if (array_key_exists('score', $vItems)) {
                            $mScore = $vItems['score'];
                        }

                        if (array_key_exists('score', $vItems)) {
                            $resultatDomicile = substr($vItems['score'], 0, 1);
                        }

                        if (array_key_exists('score', $vItems)) {
                            $resultatVisiteur = substr($vItems['score'], -1);
                        }


                        $matchs->setResultatVisiteur($resultatVisiteur);
                        $matchs->setResultatDomicile($resultatDomicile);

                        if (array_key_exists('season', $vItems['details']['contest'])) {
                            $matchs->setSeason($vItems['details']['contest']['season']);
                        }

                        // $vChampionat = $em->getRepository(self::ENTITY_CHAMPIONAT)->findOneBy(array('nomChampionat' => $vItems['details']['contest']['competition']['eng_cs']));
                        // $matchs->setChampionat($vChampionat);
                        if (array_key_exists('current-state', $vItems)) {
                            $matchs->setPeriod($vItems['current_state']['period']);
                            $matchs->setMinute($vItems['current_state']['minute']);
                        }

                        $nbLocalME = $em->getRepository(self::ENTITY_MATCH_EVENT)->findByMatchs($matchs);

                        $nbGoalApiME = $vItems['events'];
                        //   var_dump(count($nbLocalME)); die;
                        if (count($nbLocalME) < count($nbGoalApiME) && count($nbGoalApiME) > 0) {

                            if (count($nbLocalME) == 0) {
                                foreach ($vItems['events'] as $vEventItems) {
                                    $matchsEvent = new MatchsEvent();
                                    $matchsEvent->setMinute($vEventItems['minute']);
                                    $matchsEvent->setMatchs($matchs);
                                    $matchsEvent->setPlayer($vEventItems['player']);
                                    if (array_key_exists('score', $vEventItems)) {
                                        $matchsEvent->setScore($vEventItems['score']);
                                    }

                                    $matchsEvent->setType($vEventItems['type']);
                                    if ($vEventItems['team'] == 'guests') {
                                        $matchsEvent->setTeams($matchs->getTeamsVisiteur());
                                        if (array_key_exists('score', $vEventItems)) {
                                            $matchsEvent->setTeamsScore(substr($vEventItems['score'], -1));
                                        }
                                    }
                                    if ($vEventItems['team'] == 'hosts') {
                                        $matchsEvent->setTeams($matchs->getTeamsDomicile());
                                        if (array_key_exists('score', $vEventItems)) {
                                            $matchsEvent->setTeamsScore(substr($vEventItems['score'], 0, 1));
                                        }
                                    }
                                    $this->getEm()->persist($matchsEvent);
                                    $this->getEm()->flush();
                                    if(array_key_exists('score', $vEventItems)){
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
                                                    AND vu.vote IS NOT NULL";
                                                $query = $em->createQuery($dqlVote);
                                                $query->setParameter('email', $connectedItems->getUsername());
                                                $result = $query->getResult();
                                                if($result){
                                                    if(!in_array($connectedItems->getDevice(), $device_token)){
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
                                            /*"message" => $vEventItems['player'] . " a marqué un but à la " . $vEventItems['minute'] . "° minute. Score:" . $vEventItems['score'],*/
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
                                    $output->writeln("insert event " . $matchsEvent->getId());

                                }
                            } else {

                                $vEventItems = end($vItems['events']);
                                $output->writeln(count($nbLocalME) . " #" . count($nbGoalApiME));
                                if (array_key_exists('score', $vEventItems)) {

                                    if ($matchs->getScore() != $vEventItems['score']) {
                                        // Si score différent alors push notification
                                        $output->writeln("A notification will be sent");

                                        $connected = $em->getRepository('ApiDBBundle:Connected')->findAll();
                                        $device_token = array();
                                        foreach ($connected as $connectedItems) {
                                            //array_push($device_token, $connectedItems->getDevice());
                                            $dqlVote = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                                                    LEFT JOIN vu.matchs as m
                                                    LEFT JOIN vu.utilisateur as u
                                                    WHERE u.email = :email
                                                    AND vu.vote IS NOT NULL";
                                            $query = $em->createQuery($dqlVote);
                                            $query->setParameter('email', $connectedItems->getUsername());
                                            $result = $query->getResult();
                                            if($result){
                                                if(!in_array($connectedItems->getDevice(), $device_token)){
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

                            $matchsEvent = new MatchsEvent();
                            $matchsEvent->setMinute($vEventItems['minute']);
                            $matchsEvent->setMatchs($matchs);
                            $matchsEvent->setPlayer($vEventItems['player']);
                            if (array_key_exists('score', $vEventItems)) {
                                $matchsEvent->setScore($vEventItems['score']);
                            }

                            $matchsEvent->setType($vEventItems['type']);
                            if ($vEventItems['team'] == 'guests') {
                                $matchsEvent->setTeams($matchs->getTeamsVisiteur());
                                if (array_key_exists('score', $vEventItems)) {
                                    $matchsEvent->setTeamsScore(substr($vEventItems['score'], -1));
                                }
                            }

                            if ($vEventItems['team'] == 'hosts') {
                                $matchsEvent->setTeams($matchs->getTeamsDomicile());
                                if (array_key_exists('score', $vEventItems)) {
                                    $matchsEvent->setTeamsScore(substr($vEventItems['score'], 0, 1));
                                }
                            }
                            $matchs->setTimestampDateMatch($vItems['timestamp_starts']);

                            $this->getEm()->persist($matchsEvent);
                            $this->getEm()->flush();
                            $output->writeln("insert event " . $matchsEvent->getId());

                        }
                        $output->writeln("Treatements of matchs " . $matchs->getId() . "was successfull");
                        $matchs->setStateGoalApi(true);
                        $em->flush();

                    }
                    else{
                       $output->writeln('Matchs is finished');
                    }
                    /* $matchsEvent->set
                     $matchsEvent->setTeamsScore();*/


                }

            } else {
                $output->writeln("No Matchs for " . $vChampionat->getFullNameChampionat() . ".....");
            }
            // var_dump($count); die;
            $output->writeln(" --- End of Championat treatement --- " . $vChampionat->getId());

        }
    }

    private function getUrlByChampionat($idChampionat){
        $em = $this->getEm();
        $data = $em->getRepository('ApiDBBundle:Championat')->find($idChampionat);
        if (!$data) {
            return false;
        }

        $apiKey = $em->getRepository('ApiDBBundle:Mention')->findAll();
        foreach($apiKey as $vApiKey){
            $apiKey = $vApiKey->getApiKeyGoalapi();
        }

        // Erreur url  mbol ts ampu date debut todo : test, mettre date debut
        $url = "http://api.xmlscores.com/matches/?c[]=" . $data->getNomChampionat() . "&f=json&e=1&l=128&b=today&open=".$apiKey;
      //  var_dump($url); die;
    //    $url = $this->getContainer()->get('kernel')->getRootDir().'/../web/json/live1.json';
        //var_dump($url); die;
        $content = file_get_contents($url);
        $arrayJson = json_decode($content, true);

        return $arrayJson;
    }


   /* private function getJson()
    {
        $em = $this->getEm();
        $data = $em->getRepository('ApiDBBundle:Championat')->findAll();
        if (!$data) {
            return false;
        }

        foreach ($data as $k => $v) {
            $nameChampionat[] = $v->getNomChampionat();
        }
        $apiKey = $em->getRepository('ApiDBBundle:ApiKey')->findAll();
        foreach($apiKey as $vApiKey){
            $apiKey = $vApiKey->getApikey();
        }

      //  $url = "http://api.xmlscores.com/matches/?c[]=" . implode('&c[]=', $nameChampionat) . "&f=json&e=1&s=0&l=128&open=".$apiKey;
        $url = $this->getContainer()->get('kernel')->getRootDir().'/../web/json/matches.json';
        $content = file_get_contents($url);

        if(!$content){
            $this->sendErrorEmail("Error when get url of goalapi");
        }

        $arrayJson = json_decode($content, true);
        return $arrayJson;

    }*/
    private function getEm(){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        return $em;
    }

    private function sendErrorEmail($body){
        $em = $this->getEm();
        $wm = $this->getContainer()->get('mail.manager');
        $mailadmin = $em->getRepository(self::ENTITY_ADMIN)->findBy(array('roles' => array('SUPER_ADMIN')));
        foreach($mailadmin as $vMailAdmin){
            $wm->setSubject("Error of treatement");
            $wm->setFrom($vMailAdmin->getEmail());
            $wm->setTo($vMailAdmin->getEmail());
            $wm->setBody($body);
            $wm->send();
        }

    }
    private function getMessagePush($vEventItems, $matchs){
        /*$msg = " <img src='". "http:dplb.arkeup.com/".$matchs->getCheminLogoDomicile() ."'width='15' height='15' /> <b>". $matchs->getEquipeDomicile()->getFullNameClub() ."</b> VS  <img src='".$matchs->getCheminLogoVisiteur()."' width='15' height='15' />  <b> ". $matchs->getEquipeVisiteur()->getFullNameClub() ."</b> <br />";*/
        $msg = "".$matchs->getEquipeDomicile()->getFullNameClub().' VS '. $matchs->getEquipeVisiteur()->getFullNameClub(). " ";
        $msg .= " But de ".$vEventItems['player']. " à la " . $vEventItems['minute'];
        $msg .= " Score " . $vEventItems['score'];
        return $msg;
    }
}