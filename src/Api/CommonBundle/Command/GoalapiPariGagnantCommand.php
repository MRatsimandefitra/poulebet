<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 05/09/2016
 * Time: 11:19
 */

namespace Api\CommonBundle\Command;


use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Matchs;
use Api\DBBundle\Entity\MatchsEvent;
use Api\DBBundle\Entity\MvtCredit;
use Api\DBBundle\Entity\Teams;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalapiPariGagnantCommand extends ContainerAwareCommand implements InterfaceDB
{

    protected function configure()
    {
        $this
            ->setName('goalapi:check:pari-gagnant')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check match gagné pour la récapitulation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $matchsVote = $em->getRepository(self::ENTITY_MATCHS)->findMatchVoteGagnant();

        $arrayCombinedGagnant = array();
        $arrayCombinedPerdu = array();

        //#### TEST DATE
        // RECUPERER L'HEURE ACTUELLE
        $dateTemp = new \DateTime('now');
        var_dump($dateTemp);
        // AJOUTER 5 MINUTES
        // SI HEURE ACTUELLE + 5MINUTES > DATEHEURE MATCH -->>BLOQUER
//            $dateTest = $matchsVote->get('date');
        $dateTest = $dateTemp->add(new \DateInterval('PT5M'));
        var_dump($dateTest);

        if($dateTemp>$dateTest){
            var_dump($dateTemp->format('Y-m-d H:i:s').'>>> '.$dateTest->format('Y-m-d H:i:s'));
        }
        else {
            var_dump($dateTemp->format('Y-m-d H:i:s').'<<<<< '.$dateTest->format('Y-m-d H:i:s'));
        }
        $dateTest = $dateTemp->sub(new \DateInterval('PT5M'));
        var_dump($dateTest);

        if($dateTemp>$dateTest){
            var_dump($dateTemp->format('Y-m-d H:i:s').'>>>> '.$dateTest->format('Y-m-d H:i:s'));
        }
        else {
            var_dump($dateTemp->format('Y-m-d H:i:s').'<<<<< '.$dateTest->format('Y-m-d H:i:s'));
        }


        //#### TEST DATE


        if($matchsVote){


            foreach($matchsVote as $kMatchsVote => $itemsMatchsVote){
                $vote = $itemsMatchsVote->getVote();
                $matchs = $itemsMatchsVote->getMatchs();
                $utilisateur = $itemsMatchsVote->getUtilisateur();
                $gagnant= null;
                if($matchs->getStatusMatch()== 'finished'){
                    $itemsMatchsVote->setGagnant(false);
                    $scr = $matchs->getScore();
                    //#### FROMAT SOCRE XX-XX
                    /*$scoreDomicile = substr($score, 0, 1);
                    $scoreVisiteur = substr($score, -1, 1);*/
                    //var_dump($scr);
                    $score = explode('-',$scr);
                    $scoreDomicile  = intval($score[0]);
                    $scoreVisiteur = intval($score[1]);
                    //#### MARCHE PAS SI COMPARAISON DE 2 CHAINES
                    if($scoreDomicile > $scoreVisiteur){
                        $gagnant = 1;
                    }
                    if($scoreVisiteur > $scoreDomicile){
                        $gagnant = 2;
                    }
                    if($scoreVisiteur === $scoreDomicile){
                        $gagnant = 0;
                    }
//                    var_dump($vote." ".$scoreDomicile."  ".$scoreVisiteur."  ".$gagnant.($vote == $gagnant));



                    if($vote == $gagnant){
                    $itemsMatchsVote->setGagnant(true);
                    $gainPotentiel = $itemsMatchsVote->getGainPotentiel();

                        $em->persist($itemsMatchsVote);
                        $em->flush();

                         if($itemsMatchsVote->getIsCombined() == 0){
                            $gainPotentiel = $itemsMatchsVote->getGainPotentiel();
                            $miseTotal = $itemsMatchsVote->getMiseTotale();
                            $mvtCredit = new MvtCredit();
                            $lastSolde = $em->getRepository(self::ENTITY_MVT_CREDIT)->findLastSolde($utilisateur->getId());
                            $idLast = $lastSolde[0][1];
                            $mvtCreditLast = $em->getRepository(self::ENTITY_MVT_CREDIT)->findOneBy(array('id' => $idLast));
                            if(!$mvtCreditLast){
                                $solde = 0 + $gainPotentiel;
                            }else{
                                $solde  = $mvtCreditLast->getSoldeCredit() + $gainPotentiel;
                            }
                            $mvtCredit->setEntreeCredit($gainPotentiel);
                            $mvtCredit->setSoldeCredit($solde);
                            $mvtCredit->setTypeCredit("GAIN PARI SIMPLE ");
                            $mvtCredit->setUtilisateur($utilisateur);
                            $em->persist($mvtCredit);
                            $em->flush();
                            $output->writeln("Mise a jour Mouvement credit :GAIN PARI SIMPLE ");
                    }

                        if($itemsMatchsVote->getIsCombined() == 1){
                            // VERIFIER SI TOUS LES MATCHS SUIVANTS L'vote_utilisateur.idMise SONT GAGNANT
                            // SI OUI
                           $voteNonGagnant = $em->getRepository(self::ENTITY_MATCHS)->findVoteCombinedNonGagnant($utilisateur->getId(),$itemsMatchsVote->getIdMise());
                           if (!$voteNonGagnant){
                                // ENTRER LE MVT DE CREDIT
                                $gainPotentiel = $itemsMatchsVote->getGainPotentiel();
                                $miseTotal = $itemsMatchsVote->getMiseTotale();
                                $mvtCredit = new MvtCredit();
                                $lastSolde = $em->getRepository(self::ENTITY_MVT_CREDIT)->findLastSolde($utilisateur->getId());
                                $idLast = $lastSolde[0][1];
                                $mvtCreditLast = $em->getRepository(self::ENTITY_MVT_CREDIT)->findOneBy(array('id' => $idLast));
                                if(!$mvtCreditLast){
                                    $solde = 0 + $gainPotentiel;
                                }else{
                                    $solde  = $mvtCreditLast->getSoldeCredit() + $gainPotentiel;
                                }
                                $mvtCredit->setEntreeCredit($gainPotentiel);
                                $mvtCredit->setSoldeCredit($solde);
                                $mvtCredit->setTypeCredit("GAIN PARI COMBINE");
                                $mvtCredit->setUtilisateur($utilisateur);
                                $em->persist($mvtCredit);
                                $em->flush();
                                $output->writeln("Mise a jour Mouvement credit :GAIN PARI COMBINE ");
                        }
                    }



                }else{
                        $itemsMatchsVote->setGagnant(false);
                        $em->persist($itemsMatchsVote);
                        $em->flush();

                    }
                }
            }


        }else{
            $output->writeln("Aucun matchs vote");
        }

        $output->writeln("Command was finised");


    }


}