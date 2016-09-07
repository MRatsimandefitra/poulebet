<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 05/09/2016
 * Time: 11:19
 */

namespace Api\CommonBundle\Command;


use Api\DBBundle\Entity\Matchs;
use Api\DBBundle\Entity\Teams;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiMatchsParChampionatCommand extends ContainerAwareCommand {
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_TEAMS = 'ApiDBBundle:Teams';
    const ENTITY_ADMIN = 'ApiDBBundle:Admin';


    protected function configure()
    {
        $this
            ->setName('goalapi:check:matchs-by-championat')
            // the short description shown while running "php bin/console list"
            ->addArgument('championat', InputArgument::OPTIONAL, 'What do you want to import?')
            ->setDescription('Check match');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');


            $championat = $input->getArgument('championat');

            if(!$championat){
                $championat = $em->getRepository(self::ENTITY_CHAMPIONAT)->findAll();

                foreach($championat as $vChampionat){
                    $output->writeln('For championat'.$vChampionat->getFullNameChampionat());
                    $data = $this->getUrlByChampionat($vChampionat->getId());
                    foreach($data['items'] as $vItems){
                       // var_dump($vItems['teams']); die;
                        $output->writeln("Treatement of ". $vItems['id']);
                        $matchs = $em->getRepository(self::ENTITY_MATCH)->find($vItems['id']);
                        if(!$matchs){
                            $matchs = new Matchs();
                        }
                        $matchs->setStateGoalApi(false);
                        $matchs->setId($vItems['id']);
                        $matchs->setStatusMatch($vItems['status']);
                        $mDate = \DateTime::createFromFormat('Y-m-d h:i', date('Y-m-d h:i', $vItems['timestamp_starts']));
                        $matchs->setDateMatch($mDate);
                        // teams visiteur
                        $teamsVisiteur = $em->getRepository(self::ENTITY_TEAMS)->findOneBy(array('idNameClub' => $vItems['teams']['guests']['id']));
                        if(!$teamsVisiteur){
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
                        if(!$teamsDomicile){
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
                        $mScore = $vItems['score'];
                        $resultatDomicile = substr($vItems['score'], 0, 1);
                        $resultatVisiteur = substr($vItems['score'], -1);
                        $matchs->setResultatVisiteur($resultatVisiteur);
                        $matchs->setResultatDomicile($resultatDomicile);
                        $matchs->setScore($mScore);
                        if(array_key_exists('season', $vItems['details']['contest']) ){
                            $matchs->setSeason($vItems['details']['contest']['season']);
                        }

                        $matchs->setChampionat($vChampionat);
                        if(array_key_exists('current-state', $vItems)){
                            $matchs->setPeriod($vItems['current-state']['period']);
                            $matchs->setMinute($vItems['current-state']['minute']);
                        }
                        $em->persist($matchs);
                        $em->flush();
                        $output->writeln("Treatements of matchs " .$matchs->getId()."was successfull");
                        $matchs->setStateGoalApi(true);
                        $em->flush();
                        $mmatchs = $em->getRepository(self::ENTITY_MATCH)->find($matchs->getId());
                        if(!$mmatchs){
                            $this->sendErrorEmail('Error to set flux from goal api with matchs'.$mmatchs->getId());
                        }
                        if($mmatchs->getStateGoalApi() == false){
                            $this->sendErrorEmail('Error to set flux from goal api with matchs'.$mmatchs->getId());
                        }
                    }
                    $output->writeln(" --- End of Championat treatement --- ".$vChampionat->getId());

                }
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
        if(!$apiKey){
            $this->sendErrorEmail('Error this no api key');
            return false;
        }
        $url = "http://api.xmlscores.com/matches/?c[]=" . $data->getNomChampionat() . "&f=json&open=".$apiKey;
        $content = file_get_contents($url);

        $arrayJson = json_decode($content, true);
        return $arrayJson;
    }
    private function getJson()
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
        $url = "http://api.xmlscores.com/matches/?c[]=" . implode('&c[]=', $nameChampionat) . "&f=json&open=".$apiKey;
        $content = file_get_contents($url);

        if(!$content){
            $this->sendErrorEmail("Error when get url of goalapi");
        }

        $arrayJson = json_decode($content, true);
        return $arrayJson;

    }
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
}