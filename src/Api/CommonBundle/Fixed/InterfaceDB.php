<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 05/10/2016
 * Time: 09:44
 */

namespace Api\CommonBundle\Fixed;


interface InterfaceDB {

    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_MVT_CREDIT = 'ApiDBBundle:MvtCredit';
    const ENTITY_VOTE_UTILISATEUR = 'ApiDBBundle:VoteUtilisateur';
    const ENTITY_NOTIF_RECAP = 'ApiDBBundle:NotificationRecapitulation';
    const ENTITY_CONNECTED = 'ApiDBBundle:Connected';
    const ENTITY_ACHAT = 'ApiDBBundle:Oeufs';
    const FORM_ACHAT  = 'Api\DBBundle\Form\OeufsType';
    const ENTITY_LOTS = 'ApiDBBundle:Lot';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const FORM_LOT = 'Api\DBBundle\Form\LotType';
    const FORM_ADMIN_DROIT = 'Api\DBBundle\Form\DroitAdminType';
    const FORM_ADMIN_DROIT_ADD_ROLES = 'Api\DBBundle\Form\DroitAdminRoleType';
    const ENTITY_LOT_CATEGORY = 'ApiDBBundle:LotCategory';
    const FORM_LOT_CATEGORY = 'Api\DBBundle\Form\LotCategoryType';

}