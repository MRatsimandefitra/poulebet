<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 05/09/2016
 * Time: 11:19
 */

namespace Api\CommonBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiMatchsParChampionatCommand extends ContainerAwareCommand {
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_TEAMS = 'ApiDBBundle:Teams';

    protected function configure()
    {
        $this
            ->setName('goalapi:check:matchs')
            // the short description shown while running "php bin/console list"
            ->addArgument('championat', InputArgument::OPTIONAL, 'What do you want to import?')
            ->setDescription('Check match');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
            $json = $this->getJson();

            
            $championat = $input->getArgument('championat');
            if(!$championat){

            }

    }

    private function getJson()
    {
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
        $content = file_get_contents($url);

        $arrayJson = json_decode($content, true);
        return $arrayJson;

    }
}