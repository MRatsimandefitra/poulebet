<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 18/08/2016
 * Time: 15:42
 */
namespace Api\CommonBundle\Command;

use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiCommand extends ContainerAwareCommand
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_TEAMS = 'ApiDBBundle:Teams';


    protected function configure()
    {
        $this
            ->setName('goalapi:check')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check every 2 minutes the json data from goal api by ywoume ;).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getJson();
        $items = $data['items'];

        $output->writeln("Set data in database");
        $this->setDataInDatabase($items, $output);
        $output->writeln("Command was successsful");

    }

    private function setDataInDatabase($items, $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        foreach ($items as $vItems) {
            /**
             * SET CHAMPIONAT
             */
            $titleChamionat = $vItems['details']['contest']['competition']['title'];

            $championat = $em->getRepository(self::ENTITY_MATCH)->findChampionat($titleChamionat);
            //season
            $season = $vItems['details']['contest']['season'];

            if (!$championat) {
                $output->writeln("new championat -> set championat in databse");
                $championat = new Championat();

            } else {
                $output->writeln("This championat exist -> no action");
                $championat = $championat[0];
            }
            $championat->setNomChampionat($titleChamionat);
            $championat->setSeason($season);
            $em->persist($championat);
            $em->flush();

            $mId = $vItems['id'];
            $mDate = \DateTime::createFromFormat('Y-m-d h:i', date('Y-m-d h:i', $vItems['timestamp_starts']));
            /**
             * Equipe
             */
            $mEquipeDomicile = $vItems['teams']['hosts']['id'];
            $mFullEquipeDomicile = $vItems['teams']['hosts']['fullname'];
            $teamsDomicile = $em->getRepository(self::ENTITY_TEAMS)->findOneBy(array('idNameClub' => $mEquipeDomicile));

            $mEquipeVisiteur = $vItems['teams']['guests']['id'];
            $mFullEquipeVisiteur = $vItems['teams']['guests']['fullname'];
            $teamsVisiteur = $em->getRepository(self::ENTITY_TEAMS)->findOneBy(array('idNameClub' => $mEquipeVisiteur));
            /**
             * Score
             */
            $mScore = $vItems['score'];
            $resultatDomicile = substr($vItems['score'], 0, 1);
            $resultatVisiteur = substr($vItems['score'], -1);
            /**
             * Status
             */
            $mStatus = $vItems['status'];

            $now = new \DateTime('now');
            $interval = $mDate->diff($now);
            $tempEcoule = 0;
            for ($a = 0; $a <= $interval->h; $a++) {
                $minutes = 60;
                if ($a == 1) {
                    $minutesRestant = $interval->i;
                    $tempEcoule = $minutes + $minutesRestant;
                } else {
                    $tempEcoule = $tempEcoule + $minutes;
                }

                if ($tempEcoule > 100) {
                    $tempEcoule = 90;
                }
            }


            $match = $em->getRepository(self::ENTITY_MATCH)->findMatchById($mId);
            if (!$match) {
                $output->writeln("This is new match withd id (create) : " . $mId);
                $match = new Matchs();
                $match->setId($mId);
            } else {
                $output->writeln("This is new match exist (update): " . $mId);
                $match = $match[0];
                //  var_dump($match); die;
            }
            $match->setStatusMatch($mStatus);
            $match->setCheminLogoDomicile($vItems['teams']['hosts']['id']);
            $match->setCheminLogoVisiteur($vItems['teams']['guests']['id']);
            $match->setDateMatch($mDate);
            $match->setScore($mScore);
            $match->setResultatDomicile($resultatDomicile);
            $match->setResultatVisiteur($resultatVisiteur);
            $match->setEquipeDomicile($mFullEquipeDomicile);
            $match->setEquipeVisiteur($mFullEquipeVisiteur);
            $match->setTeamsDomicile($teamsDomicile);
            $match->setTeamsVisiteur($teamsVisiteur);
            $match->setTempsEcoules($tempEcoule);
            $match->setChampionat($championat);
            $match->setSeason($season);
            // $this->insert($match, array('success' => 'success' , 'error' => 'error'));
            $em->persist($match);
            $em->flush();

            $output->writeln("Mtach insert : " . $mId);
        }

    }


    private function getJson()
    {
        $jsonFile = $this->getContainer()->get('kernel')->getRootDir() . '/../web/json/matches.json';
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
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
//        $url = "http://api.xmlscores.com/matches/?f=json&c[]=eng_pl&c[]=rus_pl&e=1&open=3770d7505de574df4b7d45d88b80027a";
        $content = file_get_contents($url);

        $arrayJson = json_decode($content, true);
        return $arrayJson;

    }
}