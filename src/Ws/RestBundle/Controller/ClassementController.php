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

            $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement($monday, $sunday);

            if (is_array($classement) && count($classement) > 0) {
                $total = 0;
                foreach ($classement as $kClassement => $itemsClassement) {
                    $total = $total + $itemsClassement->getClassement();
                    $result['classement'][] = array(
                        'photo' => $itemsClassement->getUtilisateur()->getCheminPhoto(),
                        'nom' => $itemsClassement->getUtilisateur()->getNom(),
                        'prenom' => $itemsClassement->getUtilisateur()->getPrenom(),
                        'classement' => $total

                    );
                }
                $result['code_error'] = 0;
                $result['success'] = true;
                $result['error'] = false;
                $result['message'] = "Success";
                return new JsonResponse($result);
            } else {
                return $this->noClassement();
            }
        } elseif ($time === 'last') {
            $monday = new \DateTime('now');
            $monday = $monday->modify('last week');
            $sunday = new \DateTime('now');
            $sunday = $sunday->modify('last sunday');
            $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement($monday, $sunday);

            if (is_array($classement) && count($classement) > 0) {
                $total = 0;
                foreach ($classement as $kClassement => $itemsClassement) {
                    $total = $total + $itemsClassement->getClassement();
                    $result['classement'] = array(
                        'photo' => $itemsClassement->getUtilisateur()->getCheminPhoto(),
                        'nom' => $itemsClassement->getUtilisateur()->getNom(),
                        'prenom' => $itemsClassement->getUtilisateur()->getPrenom(),
                        'classement' => $total

                    );
                }
                $result['code_error'] = 0;
                $result['success'] = true;
                $result['error'] = false;
                $result['message'] = "Success";
                return new JsonResponse($result);
            } else {
                return $this->noClassement();
            }

        } elseif ($time === 'global') {

            $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement();
            if (is_array($classement) && count($classement) > 0) {
                $total = 0;
                foreach ($classement as $kClassement => $itemsClassement) {
                        $total = $total + $itemsClassement->getClassement();

                        $result['classement'] = array(
                            'idUtilisateur' => $itemsClassement->getUtilisateur()->getId(),
                            'idMise' =>$itemsClassement->getIdMise(),
                            'photo' => $itemsClassement->getUtilisateur()->getCheminPhoto(),
                            'nom' => $itemsClassement->getUtilisateur()->getNom(),
                            'prenom' => $itemsClassement->getUtilisateur()->getPrenom(),
                            'classement' => $total

                        );
                }
                $result['code_error'] = 0;
                $result['success'] = true;
                $result['error'] = false;
                $result['message'] = "Success";
                return new JsonResponse($result);
            } else {
                return $this->noClassement();
            }
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
