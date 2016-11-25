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
                        var_dump('gagnant');
                        //#### TEST
                    $itemsMatchsVote->setGagnant(3);
//                    $itemsMatchsVote->setGagnant(true);
                    $itemsMatchsVote->setGagnant(true);
                    $gainPotentiel = $itemsMatchsVote->getGainPotentiel();
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
                        //#### TEST $em->flush();
                        $output->writeln("Mise a jour Mouvement credit :GAIN PARI SIMPLE ");
                    }

                    if($itemsMatchsVote->getIsCombined() == 1){
                        // VERIFIER SI TOUS LES MATCHS SUIVANTS L'vote_utilisateur.idMise SONT GAGNANT
                        // SI OUI
                        $voteCombined= $em->getRepository(self::ENTITY_MATCHS)->findUserVoteCombined($utilisateur->getId(),$itemsMatchsVote->getIdMise());
                        $gain = 0;
                        foreach($voteCombined as $vote){
                            $vt = $vote->getVote();
                            $matchs = $vote->getMatchs();
                            if($matchs->getStatusMatch()== 'finished'){
                                $scr = $matchs->getScore();
                                //#### FROMAT SOCRE XX-XX
                                /*$scoreDomicile = substr($score, 0, 1);
                                $scoreVisiteur = substr($score, -1, 1);*/
                                $score = explode('-',$scr);
                                $scoreDomicile  = $score[0];
                                $scoreVisiteur = $score[1];
                                if($scoreDomicile > $scoreVisiteur){
                                    $gagnant = 1;
                                }
                                if($scoreVisiteur > $scoreDomicile){
                                    $gagnant = 2;
                                }
                                if($scoreVisiteur === $scoreDomicile){
                                    $gagnant = 0;
                                }
                            }
                            if ($vt == $gagnant ){
                                $gain++;
                            }
                        }
                        if ($gain == count($voteCombined)){
                            // ENTRER LE MVT DE CREDIT
                            $gainPotentiel = $itemsMatchsVote->getGainPotentiel();
                            // Set gagnant pour les votes gagants
                            foreach($voteCombined as $vote){
                                $vote->setGagnant(true);
                                $em->persist($vote);
                                $em->flush();
                            }
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
                            //#### TEST$em->flush();
                            $output->writeln("Mise a jour Mouvement credit :GAIN PARI COMBINE ");
                        /*if (!in_array($itemsMatchsVote->getUtilisateur()->getId(), $arrayCombinedGagnant)) {
                            $arrayCombinedGagnant[] = array(
                                'idUtilisateur' => $itemsMatchsVote->getUtilisateur()->getId(),
                                'dateMise' => $itemsMatchsVote->getDateMise(),
                                'gainPotentiel' => $gainPotentiel
                            );

                        }*/
                        }    
                    }

                //    $em->persist($itemsMatchsVote);
                    $em->flush();

                }else{

                        $itemsMatchsVote->setGagnant(0);
                        //var_dump($gagnantGagnant());
                        $em->flush();
                    }
                }
            }
            /*if($arrayCombinedGagnant){
                foreach($arrayCombinedGagnant as $kArrayCombinedG => $itemsArrayCombinedG){
                    $gainPotentiel = $itemsArrayCombinedG['gainPotentiel'];
                    $utilisateurId = $itemsArrayCombinedG['idUtilisateur'];
                    $utilisateur = $em->getRepository(self::ENTITY_UTILISATEUR)->findOneBy(array('id' => $utilisateurId));
                    $mvtCredit = new MvtCredit();
                    $lastSolde = $em->getRepository(self::ENTITY_MVT_CREDIT)->findLastSolde($utilisateurId);
                    $idLast = $lastSolde[0][1];
                    $mvtCreditLast = $em->getRepository(self::ENTITY_MVT_CREDIT)->findOneBy(array('id' => $idLast));
                    if(!$mvtCreditLast){
                        $solde = 0;
                    }else{
                        $solde  = $mvtCreditLast->getSoldeCredit() - $gainPotentiel;
                    }
                    $mvtCredit->setUtilisateur($utilisateur);
                    $mvtCredit->setSoldeCredit($solde);
                    $mvtCredit->setEntreeCredit($gainPotentiel);
                    $mvtCredit->setTypeCredit("PAYEMENT PARI COMBINED");
                    $mvtCredit->setDateMvt(new \DateTime('now'));
                    $em->persist($mvtCredit);
                    $em->flush();
                    $output->writeln("Ajout credit combined");
                }

            }*/


        }else{
            $output->writeln("Aucun matchs vote");
        }

        $output->writeln("Command was finised");


    }


}