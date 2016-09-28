<?php
/**
 * Created by PhpStorm.
 * User: Fy
 * Date: 28/09/2016
 * Time: 20:25
 */

namespace Api\CommonBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiNotificationLiveExistCommand extends ContainerAwareCommand
{
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_UTILISATEUR  = 'ApiDBBundle:Utilisateur';

    protected function configure()
    {
        $this
            ->setName('goalapi:check:liveexist')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check match Live');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $championat = $em->getRepository(self::ENTITY_CHAMPIONAT)->findAll();
        if(!$championat){
            $output->writeln("No Championat selected");
        }
        if($championat){
            foreach($championat as $kChampionat => $itemsChampionat){

                $data = $this->getUrlByChampionat($itemsChampionat->getId());
                if($data){
                    foreach($data['items'] as $kItems => $items){
                        if($items['status'] === 'active'){
                            $date = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', $items['timestamp_starts']));
                            $now = new \DateTime('now');
                            $near = $date->modify("-1 hours");
                            if($near >= $now ){
                                $users = $em->getRepository(self::ENTITY_UTILISATEUR)->findAll();
                                $device_token = array();

                                foreach($users as $user){
                                    $devices = $user->getDevices();
                                    foreach ($devices as $device){
                                        $device_token[] = $device->getToken();
                                        array_push($device_token, $device->getToken());
                                    }
                                }
                                $messageData = array(
                                    "message"=> "Matchs bientot",
                                    "type"=>"livescore"
                                );
                                $data = array(
                                    'registration_ids' => $device_token,
                                    'data' => $messageData
                                );
                                $http = $this->getContainer()->get('http');
                                //die('okok');
                                $res = $http->sendGCMNotification($data);

                                $output->writeln($res);
                            }
                        }
                    }
                }

            }
        }


        $output->writeln("Command was succesfull");
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
        //   var_dump($url); die;
       // $url = $this->getContainer()->get('kernel')->getRootDir().'/../web/json/live1.json';
        //var_dump($url); die;
        $content = file_get_contents($url);
        $arrayJson = json_decode($content, true);

        return $arrayJson;
    }
    private function getEm(){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        return $em;
    }
}