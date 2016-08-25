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
use Api\DBBundle\Entity\Teams;
use Api\DBBundle\Entity\TeamsPays;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiTeamsClubCommand extends ContainerAwareCommand
{
    const ENTTTY_TEAMS = 'ApiDBBundle:Teams';
    const ENTTTY_TEAMS_PAYS = 'ApiDBBundle:TeamsPays';

    protected function configure()
    {
        $this
            ->setName('goalapi:teamsclub:check')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check every 2 minutes the json data from goal api by ywoume ;).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getJson();
        $i = 0;
        foreach ($data as $k =>$vData) {

                $i = $i + 1;

                foreach($vData as $kk =>$vvData){
                    if ($k == 'en') {
                        $k = 'eng';
                    }
                    $teamsPays = $this->getRepo(self::ENTTTY_TEAMS_PAYS)->findOneBy(array('codePays' => $k.'_int'));
                    $teams = $this->getRepo(self::ENTTTY_TEAMS)->findOneBy(array('idNameClub' => $kk));

                    if(!$teams){
                        $teams = new Teams();
                        $output->writeln("Create new teams - ".$i);
                    }else{
                        $output->writeln("Update teams - ".$i);
                    }
                    $teams->setTeamsPays($teamsPays)
                        ->setIdNameClub($kk)
                        ->setNomClub($vvData[0])
                        ->setCodePays($k)
                        ->setFullNameClub($vvData[1]);
                    $this->getEm()->persist($teams);
                    $this->getEm()->flush();
                }




        }

        $output->writeln("Command was successsful");

    }

    private function getRepo($repo){
        return $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository($repo);
    }
    private function getEm(){
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
    private function getJson()
    {
        $jsonFile = $this->getContainer()->get('kernel')->getRootDir() . '/../web/json/teams.json';
        $content = file_get_contents($jsonFile);
        $arrayJson = json_decode($content, true);
        return $arrayJson;

    }
}