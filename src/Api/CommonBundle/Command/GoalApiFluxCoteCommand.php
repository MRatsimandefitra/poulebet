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
        #$data =  file_get_contents($this->getContainer()->get('kernel')->getRootDir().'/../web/json/cote.xml');
        $data =  file_get_contents("http://partner.netbetsport.fr/xmlreports/fluxcotes.xml");
        $equipeDomicile = "";
        $equipeVisiteur = "";
        $dataParse = simplexml_load_string($data);
        $json = json_encode($dataParse);
        $data1 = json_decode($json, true);
        $sport = $data1['Sport'];
        foreach($sport as $kSport => $itemsSport){
            $regionList = $itemsSport['RegionList'];
            $region = $regionList['Region'];

            $competitionList = $region['CompetitionList'];
            $competition = $competitionList['Competition'];
            $matchsList = $competition['MatchList'];
            if(count($matchsList) > 1 ){
                foreach($matchsList as $kMatchList => $itemsMatchsList){
                    $matchs = $itemsMatchsList['Match'];

                    foreach($matchs as $kMatch => $itemsMatch){
                      //  var_dump($itemsMatch['Team']); die;
                        $dateMatchs = $itemsMatch['@attributes']['date'];
                        $offerList = $itemsMatch['OfferList'];
                        $offre = $offerList['Offer'];
                        $outcome = $offre['Outcome'];
                        $teams = $itemsMatch['Team'];
                        ///var_dump($outcome[0]); die;
                        $resultOdds = array();
                        foreach($outcome as $kOutcome => $itemsOutcome){
                            $resultOdds[$itemsOutcome['@attributes']['name']] = $itemsOutcome['@attributes']['odds'];
                        }

                        foreach($teams as $kTeams => $itemsTeams){

                            if($itemsTeams['@attributes']['post'] == "home"){
                                $equipeDomicile = $itemsTeams['@attributes']['name'];
                            }
                            if($itemsTeams['@attributes']['post'] == "away"){
                                $equipeVisiteur = $itemsTeams['@attributes']['name'];
                            }

                        }

                        if($dateMatchs && $equipeVisiteur && $equipeDomicile){
                            $matchs = $em->getRepository(self::ENTITY_MATCHS)->findMatchsForCote($dateMatchs, $equipeDomicile, $equipeVisiteur);
                            if($matchs){
                               // $matchs = new Matchs();
                                if(array_key_exists($equipeDomicile,$resultOdds)){
                                    $cote1 = $resultOdds[$equipeDomicile];
                                }
                                if(array_key_exists('Nul',$resultOdds)){
                                    $coteN= $resultOdds['Nul'];
                                }
                                if(array_key_exists($equipeVisiteur,$resultOdds)){
                                    $cote2= $resultOdds[$equipeVisiteur];
                                }
                                $matchs[0]->setCot1Pronostic($cote1);
                                $matchs[0]->setCoteNPronistic($coteN);
                                $matchs[0]->setCote2Pronostic($cote2);
                                if($cote1 < $coteN && $cote1 < $cote2){
                                    $matchs[0]->setMasterProno1(true);
                                    $matchs[0]->setMasterPronoN(false);
                                    $matchs[0]->setMasterProno2(false);
                                }
                                if($coteN < $cote1 && $coteN < $cote2){
                                    $matchs[0]->setMasterPronoN(true);
                                    $matchs[0]->setMasterProno1(false);
                                    $matchs[0]->setMasterProno2(false);
                                }
                                if($cote2 < $cote1 && $cote2 < $coteN){
                                    $matchs[0]->setMasterProno2(true);
                                    $matchs[0]->setMasterPronoN(false);
                                    $matchs[0]->setMasterProno1(false);
                                }
                                $em->flush();
                            }else{
                                $output->writeln("Aucun matchs trouvé");

                            }
                        }else{
                            $output->writeln("Les arguments datematch equipevisiteur, equipedomicile ne sont pas complet");
                        }

                    }
                }
            }
        }
      //  var_dump($data1['Sport'][0]['RegionList']['Region']['CompetitionList']['Competition']['MatchList'][0]['Match'][0]['OfferList']['Offer']['Outcome']); die;

        $output->writeln("Command was successsful");

    }


}