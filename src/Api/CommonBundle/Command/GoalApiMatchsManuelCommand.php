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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiMatchsManuelCommand extends ContainerAwareCommand
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_TEAMS = 'ApiDBBundle:Teams';
    const ENTITY_ADMIN = 'ApiDBBundle:Admin';


    protected function configure()
    {
        $this
            ->setName('goalapi:check:manual')
            // the short description shown while running "php bin/console list"
            ->addOption(
                'championat',
                '-ch',
                InputOption::VALUE_OPTIONAL,
                'Name of championat'
            )
            ->addOption(
                'dateDebut',
                '-db',
                InputOption::VALUE_OPTIONAL,
                'Date debut'
            )
            ->addOption(
                'dateFinale',
                '-df',
                InputOption::VALUE_OPTIONAL,
                'Date finale'
            )
           /* ->addArgument('dateDebut', InputArgument::OPTIONAL, 'What do you want to import?')
            ->addArgument('dateFinale', InputArgument::OPTIONAL, 'What do you want to import?')*/
            ->setDescription('Check manual match');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $argChampionat = $input->getOption('championat');


        if($argChampionat){
            $championat = $argChampionat;
        }else{

            $championat = 'All';
        }

        $argDateDebut = $input->getOption('dateDebut');
        if($argDateDebut){
           // var_dump($argDateDebut. ' 00:00:00'); die;
            $dateDebut = date('Y-m-d 00:00:00', strtotime($argDateDebut. ' 00:00:00'));

        }else{
           // $dateDebut = date('Y-m-d 00:00:00');
             $dateDebut = null;
        }

        $argDateFinale = $input->getOption('dateFinale');
        if($argDateFinale){
            $dateFinale = date('Y-m-d 23:59:59',  strtotime($argDateFinale. ' 23:59:59'));
            if(!$dateDebut){
                $dateDebut = date('Y-m-d 00:00:00', strtotime($argDateFinale. ' 00:00:00'));
            }
        }else{
         //   $dateFinale = date('Y-m-d 23:59:59');
           // $dateFinale = date('Y-m-d 23:59:59',  strtotime($argDateDebut. ' 23:59:59'));

                $dateFinale = null;

        }

        if (!$argChampionat && !$argDateDebut && !$argDateFinale) {
            $championat = 'All';
        }

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if($championat == 'All'){

            $championat = $em->getRepository(self::ENTITY_CHAMPIONAT)->findAll();
        }else{

            $championat = $em->getRepository(self::ENTITY_CHAMPIONAT)->findBy(array('fullNameChampionat' => $championat));
        }


        if($championat){
            foreach ($championat as $vChampionat) {
                $output->writeln('For championat' . $vChampionat->getFullNameChampionat());
                $data = $this->getUrlByChampionat($vChampionat->getId(), $dateDebut, $dateFinale);

                if($data){
                    foreach ($data['items'] as $vItems) {
                        // var_dump($vItems['teams']); die;
                        /* $sDateMatch = \DateTime::createFromFormat('Y-m-d h:i', date('Y-m-d h:i', $vItems['timestamp_starts']));
                         if($dateDebut){
                             $sDateDebutGoalApi = \DateTime::createFromFormat('Y-m-d h:i', date('Y-m-d h:i', $dateDebut));
                         }
                         if($dateFinale){
                             $sDateFinaleGoalApi = \DateTime::createFromFormat('Y-m-d h:i', date('Y-m-d h:i', $dateFinale));
                         }*/

                        if($dateDebut && $dateFinale or $dateDebut or $dateFinale){
                            $dateCurrentMatchs = date('Y-m-d h:i:s', $vItems['timestamp_starts']);
                            if($dateCurrentMatchs > $dateDebut and $dateCurrentMatchs < $dateFinale){
                                $this->treatementDataToMatch($vItems, $vChampionat, $output, $dateDebut, $dateFinale);
                            }elseif($dateCurrentMatchs > $dateDebut && !$dateFinale){
                                $this->treatementDataToMatch($vItems, $vChampionat, $output, $dateDebut);
                            }

                        }else{
                            $this->treatementDataToMatch($vItems, $vChampionat, $output);
                        }


                    }
                }

                $output->writeln(" --- End of Championat treatement --- " . $vChampionat->getId());

            }
        }


    }

    private function treatementDataToMatch($vItems, $vChampionat,  $output, $datDebut = null, $dateFinale = null){
        $output->writeln("Treatement of " . $vItems['id']);
        $matchs = $this->getEm()->getRepository(self::ENTITY_MATCH)->find($vItems['id']);
        $newMatch = false;
        if (!$matchs) {
            $matchs = new Matchs();
            $newMatch = true;
        }
        $matchs->setStateGoalApi(false);
        $matchs->setId($vItems['id']);
        $matchs->setStatusMatch($vItems['status']);
        $mDate = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', $vItems['timestamp_starts']));
        $matchs->setDateMatch($mDate);
        // teams visiteur
        $teamsVisiteur = $this->getEm()->getRepository(self::ENTITY_TEAMS)->findOneBy(array('idNameClub' => $vItems['teams']['guests']['id']));
        if (!$teamsVisiteur) {
            $output->writeln('Ajout d\'un nouveau club');
            $teamsVisiteur = new Teams();
            $teamsVisiteur->setIdNameClub($vItems['teams']['guests']['id']);
            $teamsVisiteur->setFullNameClub($vItems['teams']['guests']['fullname']);
            $teamsVisiteur->setNomClub($vItems['teams']['guests']['name']);
            $teamsVisiteur->setLogo($vItems['teams']['guests']['id']);
            $this->getEm()->persist($teamsVisiteur);
            $this->getEm()->flush();
        }
        $matchs->setTeamsVisiteur($teamsVisiteur);
        $matchs->setEquipeVisiteur($teamsVisiteur->getFullNameClub());
        // teams domicile

        $teamsDomicile = $this->getEm()->getRepository(self::ENTITY_TEAMS)->findOneBy(array('idNameClub' => $vItems['teams']['hosts']['id']));
        if (!$teamsDomicile) {
            $output->writeln('Ajout d\'un nouveau club');
            $teamsDomicile = new Teams();
            $teamsDomicile->setIdNameClub($vItems['teams']['hosts']['id']);
            $teamsDomicile->setFullNameClub($vItems['teams']['hosts']['fullname']);
            $teamsDomicile->setNomClub($vItems['teams']['hosts']['name']);
            $teamsDomicile->setLogo($vItems['teams']['hosts']['id']);
            $this->getEm()->persist($teamsDomicile);
            $this->getEm()->flush();
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
        if (array_key_exists('season', $vItems['details']['contest'])) {
            $matchs->setSeason($vItems['details']['contest']['season']);
        }

        $matchs->setChampionat($vChampionat);
        if(array_key_exists('current-state', $vItems)){
            $matchs->setPeriod($vItems['current-state']['period']);
            $matchs->setMinute($vItems['current-state']['minute']);
        }
        if($newMatch){
            $this->getEm()->persist($matchs);
        }
        $this->getEm()->flush();
        $output->writeln("Treatements of matchs " . $matchs->getId() . "was successfull");
        $matchs->setStateGoalApi(true);
        $this->getEm()->flush();
        $mmatchs = $this->getEm()->getRepository(self::ENTITY_MATCH)->find($matchs->getId());
        if (!$mmatchs) {
            $this->sendErrorEmail('Error to set flux from goal api with matchs' . $mmatchs->getId());
        }
        if ($mmatchs->getStateGoalApi() == false) {
            $this->sendErrorEmail('Error to set flux from goal api with matchs' . $mmatchs->getId());
        }
    }
    private function getUrlByChampionat($idChampionat ,$dateDebut = null, $dateFinale = null)
    {
        $em = $this->getEm();

        if($idChampionat){
            $data = $em->getRepository('ApiDBBundle:Championat')->find($idChampionat);
            $nameChampionat[] = $data->getNomChampionat();
        }

        if (!$data) {
            return false;
        }
        $apiKey = $em->getRepository('ApiDBBundle:Mention')->findAll();
        foreach ($apiKey as $vApiKey) {
            $apiKey = $vApiKey->getApiKeyGoalapi();
        }
        if (!$apiKey) {
            $this->sendErrorEmail('Error this no api key');
            return false;
        }


        if($dateDebut && !$dateFinale){
            $timestampDateDebut = strtotime($dateDebut);
            $url = "http://api.xmlscores.com/matches/?c[]=" . $data->getNomChampionat()  . "&f=json&e=1&s=0&l=128&open=" . $apiKey.'&t1='.$timestampDateDebut;
        }
        if($dateDebut && $dateFinale){
            $timestampDateDebut = strtotime($dateDebut);
            $timestampDateFinale = strtotime($dateFinale);
            $url = "http://api.xmlscores.com/matches/?c[]=" . $data->getNomChampionat()  . "&f=json&&e=1&s=0&l=128&open=" . $apiKey.'&t1='.$timestampDateDebut.'&t2='.$timestampDateFinale;
        }
        if(!$dateDebut && !$dateFinale){
            $url = "http://api.xmlscores.com/matches/?c[]=" . $data->getNomChampionat() . "&f=json&e=1&s=0&l=128&open=" . $apiKey;
        }
        $content = file_get_contents($url);

        $arrayJson = json_decode($content, true);

        return $arrayJson;
    }

    private function getJson($dateDebut = null, $dateFinale = null, $championat = null)
    {
        $em = $this->getEm();
        if($dateDebut){
            $data = $em->getRepository('ApiDBBundle:Championat')->findAll();
        }else{
            $data = $em->getRepository('ApiDBBundle:Championat')->findOneBy(array('fullNameChampionat' => $championat));
        }


        if (!$data) {

            return false;
        }

        foreach ($data as $k => $v) {
            $nameChampionat[] = $v->getNomChampionat();
        }
        $apiKey = $em->getRepository('ApiDBBundle:ApiKey')->findAll();
        foreach ($apiKey as $vApiKey) {
            $apiKey = $vApiKey->getApikey();
        }
        $url = "http://api.xmlscores.com/matches/?c[]=" . implode('&c[]=', $nameChampionat) . "&f=json&open=" . $apiKey;

        $content = file_get_contents($url);

        if (!$content) {
            $this->sendErrorEmail("Error when get url of goalapi");
        }

        $arrayJson = json_decode($content, true);
        return $arrayJson;

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
}