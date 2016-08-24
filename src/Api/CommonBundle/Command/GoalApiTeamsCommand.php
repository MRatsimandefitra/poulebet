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
use Api\DBBundle\Entity\TeamsPays;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiTeamsCommand extends ContainerAwareCommand
{
    const ENTTTY_TEAMS = 'ApiDBBundle:TeamsPays';

    protected function configure()
    {
        $this
            ->setName('goalapi:teams:check')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check every 2 minutes the json data from goal api by ywoume ;).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getJson();

        foreach ($data['int'] as $k =>$vData) {
            $teamsPays = $this->getRepo(self::ENTTTY_TEAMS)->findOneBy(array('codePays' => $k));
            if(!$teamsPays){
                $teamsPays = new TeamsPays();
                $output->writeln("Create new pays - ");
            }else{
                $output->writeln("Update pays - ");
            }

            $teamsPays->setCodePays($k)
                ->setFullName($vData[1])
                ->setName($vData[0]);

            $this->getEm()->persist($teamsPays);
            $this->getEm()->flush();
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