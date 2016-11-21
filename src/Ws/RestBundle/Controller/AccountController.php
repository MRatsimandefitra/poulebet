<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends ApiController implements InterfaceDB
{
    public function postGetAccountProfilAction(Request $request)
    {
        $token = $request->get('token');

        if (!$token) {
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if (!$user) {
            return $this->noUser();
        }

        $result = array();
        if ($user && is_object($user)) {
            $result['account'] = array();
            /*  $result['account'] = array(
                  'photo'=> 'http://dplb.arkeup.com/upload/utilisateur/'. $user->getCheminPhoto(),
              );*/
            $photo = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
          //  $result['photo'] = 'http://dplb.arkeup.com/upload/admin/users/' . $photo->getCheminPhoto();
            if ($photo) {
                $result['account'] = array(
                   'photo'=>  'http://dplb.arkeup.com/upload/admin/users/' . $photo->getCheminPhoto()
            );
            }


            if ($user->getUsername()) {
                $result['account']['nomAffiche'] = $user->getUsername();
            } elseif ($user->getPrenom()) {
                $result['account']['nomAffiche'] = $user->getPrenom();
            }
        }
        //nbPoulet
        $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
        $idLast = $lastSolde[0][1];
        $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
        if ($mvtCreditLast) {
            $result['solde'] = $mvtCreditLast->getSoldeCredit();
        } else {
            $result['solde'] = 0;
        }
        //mise
        $this->getRepo(self::ENTITY_MATCHS)->findTotalGainsOfUser($user->getId());
        // gains
        $gains = $this->getRepo(self::ENTITY_MATCHS)->findTotalGainsOfUser($user->getId());
        if (is_array($gains) && count($gains) > 0) {
            $totalGains = 0;
            foreach ($gains as $kGains => $itemsGains) {
                $totalMise = $totalGains + $itemsGains->getMiseTotale();
            }
            $result['totalMiseTotal'] = $totalMise;

        } else {
            $result['totalMiseTotal'] = 0;
        }
        if (is_array($gains) && count($gains) > 0) {
            $totalGains = 0;
            foreach ($gains as $kGains => $itemsGains) {
                $totalGains = $totalGains + $itemsGains->getGainPotentiel();
            }
            $result['totalGain'] = $totalGains;

        } else {
            $result['totalGain'] = 0;
        }
        $totalEncours = $this->getRepo(self::ENTITY_MATCHS)->findTotalMatchsEnCours($user->getId());

        if (is_array($totalEncours) && count($totalEncours) > 0) {
            ;
            $totalEnCour = 0;
            foreach ($totalEncours as $itemsTotalEnCours) {
                $totalEnCour = $totalEnCour + $itemsTotalEnCours->getMiseTotale();
            }
            $result['totalEnCours'] = $totalEnCour;
        } else {
            $result['totalEnCours'] = 0;
        }
        // photo profil

        //   var_dump($result); die;
        // championat
        $championat = $this->getRepo(self::ENTITY_MATCHS)->findRecapitulationForUserForChampionat($user->getId());
        if (is_array($championat) && count($championat) > 0) {
            foreach ($championat as $kChampionat => $itemsChampionat) {
                $result['list_championat'][] = array(
                    'idChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getId(),
                    'nomChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getFullNameChampionat(),
                );
            }
        }
        // 2 dernier concours
        $concours = $this->getRepo(self::ENTITY_MATCHS)->findTwoLastConcour();
        if ($concours) {

        }
        //recapitulation par utilisateur
        $recapitulation = $this->getRepo(self::ENTITY_MATCHS)->findRecapitulationForUser($user->getId());


        if (is_array($recapitulation) && count($recapitulation) > 0) {
            $dataIsGagne = true;
            foreach ($recapitulation as $kRecapitulation => $itemsMatch) {
                if ($itemsMatch->getIsCombined() === false) {
                    $result['list_match'][] = array(
                        'idMatch' => $itemsMatch->getMatchs()->getId(),
                        'dateMatch' => $itemsMatch->getMatchs()->getDateMatch(),
                        'equipeDomicile' => $itemsMatch->getMatchs()->getEquipeDomicile(),
                        'equipeVisiteur' => $itemsMatch->getMatchs()->getEquipeVisiteur(),
                        'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatch->getMatchs()->getCheminLogoDomicile() . '.png',// $itemsMatchData->getTeamsDomicile()->getLogo(),
                        'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatch->getMatchs()->getCheminLogoVisiteur() . '.png',// $itemsMatchData->getTeamsVisiteur()->getLogo(),
                        'score' => $itemsMatch->getMatchs()->getScore(),
                        'scoreDomicile' => substr($itemsMatch->getMatchs()->getScore(), 0, 1),
                        'scoreVisiteur' => substr($itemsMatch->getMatchs()->getScore(), -1, 1),
                        'status' => $itemsMatch->getMatchs()->getStatusMatch(),
                        'tempsEcoules' => $itemsMatch->getMatchs()->getTempsEcoules(),
                        'live' => ($itemsMatch->getMatchs()->getStatusMatch() == 'active') ? true : false,
                        'master_prono_1' => $itemsMatch->getMatchs()->getMasterProno1(),
                        'master_prono_n' => $itemsMatch->getMatchs()->getMasterPronoN(),
                        'master_prono_2' => $itemsMatch->getMatchs()->getMasterProno2(),
                        'cote_pronostic_1' => $itemsMatch->getCote1(),
                        'cote_pronostic_n' => $itemsMatch->getCoteN(),
                        'cote_pronostic_2' => $itemsMatch->getCote2(),
                        'voted_equipe' => $itemsMatch->getVote(),
                        'isCombined' => $itemsMatch->getIsCombined(),
                        'gainsPotentiel' => $itemsMatch->getGainPotentiel(),
                        'miseTotal' => $itemsMatch->getMiseTotale(),
                        'idChampionat' => $itemsMatch->getMatchs()->getChampionat()->getId(),
                        //    'isGagne' => $this->getStatusRecap($itemsMatch->getId()),
                        'isGagne' => $itemsMatch->getGagnant(),
                        'imageFacebook' => ($this->getIsGagne($itemsMatch->getId(), 'http://dplb.arkeup.com/upload/admin/facebook/' . $itemsMatch->getIdMise(), $itemsMatch->getDateMise())) ? 'http://dplb.arkeup.com/upload/admin/facebook/' . $this->getObjectRepoFrom(self::ENTITY_FACEBOOK, array())->getImageOeuf() : 'http://dplb.arkeup.com/upload/admin/facebook/' . $this->getObjectRepoFrom(self::ENTITY_FACEBOOK, array())->getImagePoulebet()
                    );

                } else {
                    
                    if ($this->getStatusRecap($itemsMatch->getId(), $itemsMatch->getIdMise(), $itemsMatch->getDateMise()) === false) {
                        $dataIsGagne = false;
                    }
                    if ($itemsMatch->getMatchs()->getStatusMatch() != 'finished') {
                        $dataStatus = 'En cours';
                    } elseif ($dataIsGagne === true) {
                        $dataStatus = "Gagné";
                    } else {
                        $dataStatus = "Terminé";
                    }
                    $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForRecapCombined($user->getId(), $itemsMatch->getIdMise());
                    $arrayMatch = array();
                    foreach ($matchs as $kMatchs => $itemsMatch) {
//                        var_dump($itemsMatch); die;
                        $arrayMatch[] = array(
                            'idMatch' => $itemsMatch->getMatchs()->getId(),
                            'dateMatch' => $itemsMatch->getMatchs()->getDateMatch(),
                            'equipeDomicile' => $itemsMatch->getMatchs()->getEquipeDomicile(),
                            'equipeVisiteur' => $itemsMatch->getMatchs()->getEquipeVisiteur(),
                            'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatch->getMatchs()->getCheminLogoDomicile() . '.png',// $itemsMatchData->getTeamsDomicile()->getLogo(),
                            'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatch->getMatchs()->getCheminLogoVisiteur() . '.png',// $itemsMatchData->getTeamsVisiteur()->getLogo(),
                            'score' => $itemsMatch->getMatchs()->getScore(),
                            'scoreDomicile' => substr($itemsMatch->getMatchs()->getScore(), 0, 1),
                            'scoreVisiteur' => substr($itemsMatch->getMatchs()->getScore(), -1, 1),
                            'status' => $itemsMatch->getMatchs()->getStatusMatch(),
                            'tempsEcoules' => $itemsMatch->getMatchs()->getTempsEcoules(),
                            'live' => ($itemsMatch->getMatchs()->getStatusMatch() == 'active') ? true : false,
                            'master_prono_1' => $itemsMatch->getMatchs()->getMasterProno1(),
                            'master_prono_n' => $itemsMatch->getMatchs()->getMasterPronoN(),
                            'master_prono_2' => $itemsMatch->getMatchs()->getMasterProno2(),
                            'cote_pronostic_1' => $itemsMatch->getCote1(),
                            'cote_pronostic_n' => $itemsMatch->getCoteN(),
                            'cote_pronostic_2' => $itemsMatch->getCote2(),
                            'voted_equipe' => $itemsMatch->getVote(),

                            //  'isGagne' => $this->getStatusRecap($itemsMatch->getId())
                        );
                    }
                    $result['list_match'][] = array(
                        'miseId' => $itemsMatch->getIdMise(),
                        'gainsPotentiel' => $itemsMatch->getGainPotentiel(),
                        'miseTotal' => $itemsMatch->getMiseTotale(),
                        'matchs' => $arrayMatch,
                        'gagnantCombine' => $itemsMatch->getGagnant(),
                        'imageFacebook' => ($dataIsGagne) ? $this->getObjectRepoFrom(self::ENTITY_FACEBOOK, array())->getImageOeuf() : 'http://dplb.arkeup.com/upload/admin/facebook/'.$this->getObjectRepoFrom(self::ENTITY_FACEBOOK, array())->getImagePoulebet(),
                        'statusCombine' => $dataStatus,
                        'isCombined' => $itemsMatch->getIsCombined()
                    );
                }

                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Success";
            }

        } else {
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucune récapitulatio trouvée";
        }
        return new JsonResponse($result);
    }

    public function postGetUploadPhotoAction(Request $request)
    {
        $binaryPhoto = $request->get('image');
        if (!$binaryPhoto) {
            return $this->noImage();
        }
        $token = $request->get('token');
        if (!$token) {
            return $this->noToken();
        }

        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if (!$user) {
            return $this->noUser();
        }
        $nameImage = uniqid() . '.png';
        $uploadUrl = $this->get('kernel')->getRootDir() . '/../web/upload/admin/users/' . $nameImage;
        $ifp = fopen($uploadUrl, "w+");
        fwrite($ifp, base64_decode($binaryPhoto));
        fclose($ifp);
        $user->setCheminPhoto($nameImage);
        $this->insert($user, array('success' => 'success', 'error' => 'error'));
        $photo = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        $result['photo'] = 'http://dplb.arkeup.com/upload/admin/users/' . $photo->getCheminPhoto();
        $result['code_error'] = 0;
        $result['success'] = true;
        $result['error'] = false;
        $result['message'] = "image envoyé avec succes";
        return new JsonResponse($result);

    }

    //private function no
    private function noUser()
    {
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Le token doit être spécifié";
        return new JsonResponse($result);
    }

    private function noToken()
    {
        $result['code_error'] = 4;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le token doit être spécifié";
        return new JsonResponse($result);
    }

    private function getStatusRecap($idVoteUtilisateur)
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->findStatusRecap($idVoteUtilisateur);
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $kData => $itemsData) {
                $gagnant = $itemsData->getGagnant();
                $vote = $itemsData->getVote();
                if ($gagnant == $vote) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    private function noImage()
    {
        $result['code_error'] = 4;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Image doit être précisé";
        return new JsonResponse($result);
    }

    private function getIsGagne($idVote, $idMise, $date)
    {
        $matchs = $this->getRepo(self::ENTITY_MATCHS)->findRecapMatchGagnant($idVote, $idMise, $date);
        if (is_array($matchs) && count($matchs) > 0) {
            foreach ($matchs as $kMatchs => $itemsMatchs) {
                $statusMatchs = $itemsMatchs->getMatchs()->getStatusMatchs();
                if ($statusMatchs === 'finished') {
                    $gagnant = $itemsMatchs->getGagnat();
                    if ($gagnant === 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}
