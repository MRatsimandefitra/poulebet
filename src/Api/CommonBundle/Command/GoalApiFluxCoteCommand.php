<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 18/08/2016
 * Time: 15:42
 */
namespace Api\CommonBundle\Command;

use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class GoalApiFluxCoteCommand extends ContainerAwareCommand implements InterfaceDB
{
    protected function configure()
    {
        $this
            ->setName('goalapi:check:cote')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check every 2 minutes the json data from goal api by ywoume ;).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $data =  file_get_contents($this->getContainer()->get('kernel')->getRootDir().'/../web/json/cote.xml');

        $dataParse = simplexml_load_string($data);
        $json = json_encode($dataParse);
        $data1 = json_decode($json, true);
        $sport = $data1['Sport'];
        foreach($sport as $kSport => $itemsSport){
            $region = $itemsSport['RegionList']['region'];
            $region = $itemsSport['Competition'];
        }
        var_dump($data1['Sport'][0]['RegionList']['Region']['CompetitionList']['Competition']['MatchList'][0]['Match'][0]['OfferList']['Offer']['Outcome']); die;

        $output->writeln("Command was successsful");

    }


}