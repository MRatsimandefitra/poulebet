<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ClassementController extends ApiController implements InterfaceDB
{
    /**
     * Ws, récupérer la liste des classement
     * @ApiDoc(
     *  description="Ws, la liste des classement",
     *  parameters = {
     *          {"name" = "time", "dataType"="string" ,"required"=true, "description"= "time choix entre now, last, global"}
     *      }
     * )
     */
    public function postGetListClassementAction(Request $request)
    {
        $time = $request->get('time');

        if (is_null($time)) {
            return $this->noTime();
        }
        $result = array();
        if ($time === 'now') {
            $tmpSunday = new \DateTime('now');
            $monday = $tmpSunday->modify('last monday');

            $tmpSunday = new \DateTime('now');
            $sunday = $tmpSunday->modify('next sunday');

            $userClassement  =  $this->getRepo(self::ENTITY_MATCHS)->findClassement($monday, $sunday, null, true);
            foreach($userClassement as $kUserClassement => $itemsUserClassement){
                $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement($monday, $sunday, $itemsUserClassement->getUtilisateur()->getId());
                if (is_array($classement) && count($classement) > 0) {
                    $total = 0;
                    foreach ($classement as $kClassement => $itemsClassement) {
                        $total = $total + $itemsClassement->getClassement();
                        $nom = $itemsClassement->getUtilisateur()->getNom();
                        $prenom  = $itemsClassement->getUtilisateur()->getPrenom();
                        $photo = $itemsClassement->getUtilisateur()->getCheminPhoto();
                        $tmpResult = array(
                            'id' => $itemsClassement->getUtilisateur()->getId(),
                            'nom' =>   $nom,
                            'prenom' =>  $prenom,
                            'photo' => $photo,
                            'classement' => $total
                        );

                    }
                    $result['list_classement'][] = $tmpResult;

                } else {
                    return $this->noClassement();
                }
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
            return new JsonResponse($result);
        } elseif ($time === 'last') {
            $monday = new \DateTime('now');
            $monday = $monday->modify('last week');
            $sunday = new \DateTime('now');
            $sunday = $sunday->modify('last sunday');

            $userClassement  =  $this->getRepo(self::ENTITY_MATCHS)->findClassement($monday, $sunday, null, true);
            foreach($userClassement as $kUserClassement => $itemsUserClassement){
                $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement($monday, $sunday, $itemsUserClassement->getUtilisateur()->getId());
                if (is_array($classement) && count($classement) > 0) {
                    $total = 0;
                    foreach ($classement as $kClassement => $itemsClassement) {
                        $total = $total + $itemsClassement->getClassement();
                        $nom = $itemsClassement->getUtilisateur()->getNom();
                        $prenom  = $itemsClassement->getUtilisateur()->getPrenom();
                        $photo = $itemsClassement->getUtilisateur()->getCheminPhoto();
                        $tmpResult = array(
                            'id' => $itemsClassement->getUtilisateur()->getId(),
                            'nom' =>   $nom,
                            'prenom' =>  $prenom,
                            'photo' => $photo,
                            'classement' => $total
                        );

                    }
                    $result['list_classement'][] = $tmpResult;

                } else {
                    return $this->noClassement();
                }
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
            return new JsonResponse($result);




        } elseif ($time === 'global') {

            $userClassement  =  $this->getRepo(self::ENTITY_MATCHS)->findClassement(null, null, null, true);
            foreach($userClassement as $kUserClassement => $itemsUserClassement){
                $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement(null, null, $itemsUserClassement->getUtilisateur()->getId());
                if (is_array($classement) && count($classement) > 0) {
                    $total = 0;
                    foreach ($classement as $kClassement => $itemsClassement) {
                        $total = $total + $itemsClassement->getClassement();
                        $nom = $itemsClassement->getUtilisateur()->getNom();
                        $prenom  = $itemsClassement->getUtilisateur()->getPrenom();
                        $photo = $itemsClassement->getUtilisateur()->getCheminPhoto();
                        $tmpResult = array(
                            'id' => $itemsClassement->getUtilisateur()->getId(),
                            'nom' =>   $nom,
                            'prenom' =>  $prenom,
                            'photo' => $photo,
                            'classement' => $total
                        );

                    }
                    $result['list_classement'][] = $tmpResult;

                    /*
                    $classementArray = array();
                    foreach($result['list_classement'] as $kListClassement =>$itemsListClassement){
                        $classementArray[] = $itemsListClassement['classement'];
                        var_dump($itemsListClassement['classement']);
                    }
                    die('okok');
                    var_dump($classementArray); die;
                    var_dump($result); die;*/
                } else {
                    return $this->noClassement();
                }
            }
            //var_dump($result['list_classement']); die;
            $arrayListClassement = array();
            foreach($result['list_classement'] as $kListClassement => $itemsListClassement){
                $arrayListClassement[] = $itemsListClassement['classement'];
            }
            arsort($arrayListClassement);
            foreach($arrayListClassement as $k => $itemsArrayListClassement){
                if($itemsArrayListClassement === $result['list_classement'][$k]['classement']){

                    $arrayResult[] = array(
                        'id' => $result['list_classement'][$k]['id'],
                        'nom' =>  $result['list_classement'][$k]['nom'],
                        'prenom' =>  $result['list_classement'][$k]['prenom'],
                        'photo' => $result['list_classement'][$k]['photo'],
                        'classement' => $itemsArrayListClassement
                    );
                }

            }
            $result['list_classement'] = $arrayResult;
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
            return new JsonResponse($result);
        }
        $result = array();


        return new JsonResponse($result);
    }

    private function noTime()
    {
        $result['code_error'] = 4;
        $result['success'] = false;
        $result['error'] = true;
        $result['message'] = "Le time doit être spécifié";
        return new JsonResponse($result);
    }

    private function noClassement(){
        $result['code_error'] = 0;
        $result['success'] = true;
        $result['error'] = false;
        $result['message'] = "Aucun classement en cours";
        return new JsonResponse($result);
    }
}
