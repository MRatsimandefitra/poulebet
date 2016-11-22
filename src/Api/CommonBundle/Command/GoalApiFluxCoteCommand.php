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
use Api\DBBundle\Entity\MatchsFluxCote;
use Api\DBBundle\Entity\TeamsCoresspondance;
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
        // var_dump($data); die;
        $data = file_get_contents("http://partner.netbetsport.fr/xmlreports/fluxcotes.xml");
        $equipeDomicile = "";
        $equipeVisiteur = "";

        /*      $dataParse = simplexml_load_string($data);
              var_dump($dataParse); die;
              $json = json_encode($data);*/

        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
        //  $xml = simplexml_load_string($xml);
        $json = json_encode($xml);
        $file = "cotenetbet.txt";
        file_put_contents($file, $json);
        $data1 = json_decode($json, true);
        //var_dump($data1['SportList']['Sport']);

        $sport = $data1['SportList']['Sport'];

        foreach ($sport as $kSport => $itemsSport) {
            if ($itemsSport['@attributes']['name'] === 'Football') {
                $regionList = $itemsSport['RegionList'];
                $region = $regionList['Region'];
                $regionName = $region['@attributes']['name'];
                $competitionList = $region['CompetitionList'];
                $competition = $competitionList['Competition'];
                $championat = $competition['@attributes']['name'];
             //   var_dump($competition['@attributes']['name']); die;
                if (array_key_exists('MatchList', $competition)) {
                    $matchsList = $competition['MatchList'];

                    if (count($matchsList) > 1) {
                        $count = 0;
                        foreach ($matchsList as $kMatchList => $itemsMatchsList) {
                            $count = $count + 1;
                            if (array_key_exists('Match', $itemsMatchsList)) {
                                $matchs = $itemsMatchsList['Match'];

                                foreach ($matchs as $kMatch => $itemsMatch) {

                                    //  var_dump($itemsMatch['Team']); die;
                                    $dateMatchs = "";
                                    if (array_key_exists('@attributes', $itemsMatch)) {
                                        $dateMatchs = $itemsMatch['@attributes']['date'];

                                    }
                                    if (array_key_exists('OfferList', $itemsMatch)) {

                                        $offerList = $itemsMatch['OfferList'];
                                        $offre = $offerList['Offer'];
                                        if (array_key_exists('Outcome', $offre)) {

                                            $outcome = $offre['Outcome'];
                                            if (array_key_exists('Team', $itemsMatch)) {
                                                $teams = $itemsMatch['Team'];

                                                foreach($teams as $kTeams => $itemsTeams){
                                                   // var_dump($itemsTeams); die;
                                                    $teamsCorres = $em->getRepository(self::ENTITY_TEAMS_CORRES)->findOneBy(array('teams' => $itemsTeams['@attributes']['name']));
                                                    $newTeamsCorres = false;
                                                    if(!$teamsCorres){
                                                        $teamsCorres = new TeamsCoresspondance();
                                                        $newTeamsCorres = true;
                                                    }
                                                    $teamsCorres->setRegion($regionName);
                                                    $teamsCorres->setTeams($itemsTeams['@attributes']['name']);
                                                    $temasGoalApi = $em->getRepository(self::ENTITY_TEAMS)->findTeamsByFluxCote($itemsTeams['@attributes']['name']);
                                                    if(!empty($temasGoalApi)){
                                                        $isExist = true;
                                                        $teamsCorres->setTeamsNameInGoalApi($temasGoalApi[0]->getNomClub());
                                                        $teamsCorres->setTeamsFullNameInGoalApi($temasGoalApi[0]->getFullNameClub());
                                                    }else{
                                                        $isExist = false;
                                                    }
                                                    $teamsCorres->setIsExistInGoalApi($isExist);
                                                    if($newTeamsCorres){
                                                        $em->persist($teamsCorres);
                                                    }

                                                    $em->flush();

                                                }

                                                ///var_dump($outcome[0]); die;
                                                $resultOdds = array();
                                                foreach ($outcome as $kOutcome => $itemsOutcome) {
                                                    if (array_key_exists('@attributes', $itemsOutcome)) {

                                                        $resultOdds[$itemsOutcome['@attributes']['name']] = $itemsOutcome['@attributes']['odds'];
                                                    }

                                                }
                                                
                                                foreach ($teams as $kTeams => $itemsTeams) {
                                                    if (array_key_exists('@attributes', $itemsTeams)) {
                                                        if ($itemsTeams['@attributes']['post'] == "home") {
                                                            $equipeDomicile = $itemsTeams['@attributes']['name'];
                                                        }
                                                        if ($itemsTeams['@attributes']['post'] == "away") {
                                                            $equipeVisiteur = $itemsTeams['@attributes']['name'];
                                                        }
                                                    }
                                                }
                                                $home="";
                                                $away="";
                                                if ($equipeDomicile == "CSKA Moscou" && $equipeVisiteur == "Bayer Leverkusen"){
                                                    $json = json_encode($resultOdds);
                                                    file_put_contents("odds.json", $json);
                                                    $home = "CSKA Moscou";
                                                    $away="Bayer Leverkusen";
                                                }
                                                
                                                if ($dateMatchs && $equipeVisiteur && $equipeDomicile) {

                                                    // correspondance

                                                    $matchsCorresDomicile = $em->getRepository(self::ENTITY_MATCHS_CORRESPONDANT)->findCorrespondanceEquipeDomicile($equipeDomicile);
                                                    if($matchsCorresDomicile){
                                                        $equipeDomicile = $matchsCorresDomicile[0]->getEquipeGoalApi();
                                                    }

                                                    $matchsCorresVisiteur = $em->getRepository(self::ENTITY_MATCHS_CORRESPONDANT)->findCorrespondanceEquipeVisiteur($equipeVisiteur);
                                                    if($matchsCorresVisiteur){

                                                        $equipeVisiteur = $matchsCorresVisiteur[0]->getEquipeGoalApi();
                                                    }
                                                    if ($home == "CSKA Moscou" && $away == "Bayer Leverkusen"){
                                                        $json = json_encode($resultOdds);
                                                        file_put_contents("odds.json", $equipeDomicile."VS".$equipeVisiteur,FILE_APPEND);
                                                    } 
                                                    $matchs = $em->getRepository(self::ENTITY_MATCHS)->findMatchsForCote($dateMatchs, $equipeDomicile, $equipeVisiteur);

                                                    if ($matchs) {
                                                        // $matchs = new Matchs();
                                                        if (array_key_exists($equipeDomicile, $resultOdds)) {
                                                            $cote1 = $resultOdds[$equipeDomicile];
                                                        }
                                                        if (array_key_exists('Nul', $resultOdds)) {
                                                            $coteN = $resultOdds['Nul'];
                                                        }
                                                        if (array_key_exists($equipeVisiteur, $resultOdds)) {
                                                            $cote2 = $resultOdds[$equipeVisiteur];
                                                        }
                                                        $matchs[0]->setCot1Pronostic($cote1);
                                                        $matchs[0]->setCoteNPronistic($coteN);
                                                        $matchs[0]->setCote2Pronostic($cote2);
                                                        if ($cote1 < $coteN && $cote1 < $cote2) {
                                                            $matchs[0]->setMasterProno1(true);
                                                            $matchs[0]->setMasterPronoN(false);
                                                            $matchs[0]->setMasterProno2(false);
                                                        }
                                                        if ($coteN < $cote1 && $coteN < $cote2) {
                                                            $matchs[0]->setMasterPronoN(true);
                                                            $matchs[0]->setMasterProno1(false);
                                                            $matchs[0]->setMasterProno2(false);
                                                        }
                                                        if ($cote2 < $cote1 && $cote2 < $coteN) {
                                                            $matchs[0]->setMasterProno2(true);
                                                            $matchs[0]->setMasterPronoN(false);
                                                            $matchs[0]->setMasterProno1(false);
                                                        }
                                                        $em->flush();
                                                        $output->writeln("Insert" . $matchs[0]->getId() . " ---  Numero : " . $count);
                                                    } else {

                                                        $output->writeln("Aucun matchs trouvé - ID : " . $count);
                                                    //    var_dump($da)
                                                        if (array_key_exists($equipeDomicile, $resultOdds)) {
                                                            $cote1 = $resultOdds[$equipeDomicile];
                                                        }
                                                        if (array_key_exists('Nul', $resultOdds)) {
                                                            $coteN = $resultOdds['Nul'];
                                                        }
                                                        if (array_key_exists($equipeVisiteur, $resultOdds)) {
                                                            $cote2 = $resultOdds[$equipeVisiteur];
                                                        }
                                                        $dateMatch = new \DateTime($dateMatchs);
                                                        $matchsFluxCote = $em->getRepository(self::ENTITY_MATCHS_FLUX_COTE)->findOneBy(array('dateMatch' =>$dateMatch, 'equipeDomicile' => $equipeDomicile, 'equipeVisiteur' => $equipeVisiteur ));
                                                        $newMatchsFluxCote = false;
                                                        if(!$matchsFluxCote){
                                                            $matchsFluxCote  = new MatchsFluxCote();
                                                            $newMatchsFluxCote = true;
                                                        }

                                                        $matchsFluxCote->setChampionat($championat);


                                                        $matchsFluxCote->setDateMatch($dateMatch);
                                                        $matchsFluxCote->setEquipeDomicile($equipeDomicile);
                                                        $matchsFluxCote->setEquipeVisiteur($equipeVisiteur);
                                                        $matchsFluxCote->setRegion($regionName);
                                                        $matchsFluxCote->setCote1($cote1);
                                                        $matchsFluxCote->setCote2($cote2);
                                                        $matchsFluxCote->setCoteN($coteN);
                                                        if($newMatchsFluxCote){
                                                            $em->persist($matchsFluxCote);
                                                        }

                                                        $em->flush();
                                                        $output->writeln("insert matchs inxistant sur goalapi");
                                                    }
                                                } else {
                                                    $output->writeln("Les arguments datematch equipevisiteur, equipedomicile ne sont pas complet");
                                                }
                                            }

                                        }


                                    }
                                }

                            }

                        }
                    }
                }


            }

        }
        //  var_dump($data1['Sport'][0]['RegionList']['Region']['CompetitionList']['Competition']['MatchList'][0]['Match'][0]['OfferList']['Offer']['Outcome']); die;

        $output->writeln("Command was successsful");

    }


}