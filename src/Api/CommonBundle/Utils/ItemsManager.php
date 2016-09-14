<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 14/09/2016
 * Time: 11:05
 */

namespace Api\CommonBundle\Utils;


class ItemsManager extends ApiManager
{

    public function getItemsCountUtilisateurTotal($args = null)
    {
        $dql = "SELECT u from ApiDBBundle:Utilisateur u";

        if($args == 'enable'){
            $dql .= " WHERE u.isEnable = 1 ";
        }
        if($args == 'disable'){
            $dql .= " WHERE u.isEnable = 0";
        }

        $query = $this->getEm()->createQuery($dql);
        $data = $query->getResult();
        return count($data);
    }



}