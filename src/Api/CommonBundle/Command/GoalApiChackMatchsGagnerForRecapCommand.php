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

class GoalApiChackMatchsGagnerForRecapCommand extends ContainerAwareCommand implements InterfaceDB
{

    protected function configure()
    {
        $this
            ->setName('goalapi:check:matchs-gagner-recap')
            // the short description shown while running "php bin/console list"
            ->setDescription('Check match gagné pour la récapitulation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $matchsVoter = $em->getRepository(self::ENTITY_MATCHS)->findMatchsForRecap();
        $arrayGagner = array();
        if($matchsVoter){
            $count = 0;
            foreach($matchsVoter as $k => $itemsMatchsVoter){
                $matchs = $em->getRepository(self::ENTITY_MATCHS)->find($itemsMatchsVoter->getMatchs()->getId());
                if(!$matchs){
                    $output->writeln("Aucun matchs");
                }
                $gagnant = null;
                if($matchs->getStatusMatch() == 'finished'){
                    $score = $matchs->getScore();
                    $scoreDomicile =substr($score, 0, 1);
                    $scoreVisiteur =substr($score, -1, 1);
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

//                $voteMatch = $em->getRepository(self::ENTITY_MATCHS)->findMatchsExisitInVote($itemsMatchsVoter->getMatchs()->getId());*
                $voteMatch = $em->getRepository(self::ENTITY_MATCHS)->findMatchsExisitInVote('2a4436e6b50383d2f3a205f11f9c829a');
                var_dump(count($voteMatch)); die;
                if(!$voteMatch){
                    $output->writeln("Aucun vote");
                }

                foreach($voteMatch as $kVoteMatchs => $itemsVoteMatchs){
                    $gainPotentiel  = $itemsVoteMatchs->getGainPotentiel();
                    $vote = $itemsVoteMatchs->getVote();

                    if($vote === $gagnant){
                        $count = $count + 1;
                        $itemsVoteMatchs->setGagnant(true);
                        $arrayGagner[] =array(
                            'IdVote' => $itemsVoteMatchs->getId(),
                            'utilisateurId' => $itemsVoteMatchs->getUtilisateur()->getId(),
                            'vote' => $vote,
                            'matchsId' => $itemsVoteMatchs->getMatchs()->getId()
                        );
                        //$mvtCredit->setSoldeCredit($)
                    }else{
                        $itemsVoteMatchs->setGagnant(false);
                    }


                  /*  $em->persist($itemsVoteMatchs);
                    $em->flush();*/
                    $output->writeln("Mise a Gagnant ");
                }


                $output->writeln("Mise à jour");
            }

            var_dump($arrayGagner); die;

            foreach($arrayGagner as $kArrayGagner => $itemsArrayGagner){
                // var_dump($itemsArrayGagner); die;
                $mvtCredit = new MvtCredit();
                $lastSolde = $em->getRepository(self::ENTITY_MVT_CREDIT)->findLastSolde($itemsArrayGagner['utilisateurId']);
                $idLast = $lastSolde[0][1];

                $mvtCreditLast = $em->getRepository(self::ENTITY_MVT_CREDIT)->findOneBy(array('id' => $idLast));
                if(!$mvtCreditLast){
                    $solde = 0 + $gainPotentiel;
                }else{
                    $solde  = $mvtCreditLast->getSoldeCredit() + $gainPotentiel;
                }
                $mvtCredit->setEntreeCredit($gainPotentiel);
                $mvtCredit->setSoldeCredit($solde);
                $mvtCredit->setTypeCredit("PAYEMENT ");
                $mvtCredit->setUtilisateur($em->getRepository(self::ENTITY_UTILISATEUR)->findOneBy(array('id' => $itemsArrayGagner['utilisateurId'])));
                /*$em->persist($mvtCredit);
                $em->flush();*/
                $output->writeln("Mise a jour Mouvement credit ");

            }
        }
    }


}