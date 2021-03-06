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
    const ENTITY_ADMIN = 'ApiDBBundle:Admin';
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
    const ENTITY_MATCHS_CORRESPONDANT = 'ApiDBBundle:MatchsCorrespondance';
    const ENTITY_TEAMS = 'ApiDBBundle:Teams';
    const  ENTITY_TEAMS_CORRES = 'ApiDBBundle:TeamsCoresspondance';
    const ENTITY_MATCHS_FLUX_COTE = 'ApiDBBundle:MatchsFluxCote';
    const ENTITY_PUB  = 'ApiDBBundle:Publicite';
    const FORM_PUB ='Api\DBBundle\Form\PubliciteType';
    const ENTITY_PAYS = 'ApiDBBundle:Pays';
    const FORM_PAYS ='Api\DBBundle\Form\PaysType';
    const ENTITY_REGION = 'ApiDBBundle:Region';
    const FORM_REGION ='Api\DBBundle\Form\RegionType';

    const ENTITY_PARAMETER_MAIL = 'ApiDBBundle:ParameterMail';
    const ENTITY_ADDRESS_LIVRAISON = 'ApiDBBundle:AddressLivraison';
    const FORM_ADDRESS_LIVRAISON ='Api\DBBundle\Form\AddressLivraisonType';
    const FORM_MENTION = 'Api\DBBundle\Form\MentionType';
    const ENTITY_MENTION = 'ApiDBBundle:Mention';
    const ENTITY_APIKEY = 'ApiDBBundle:ApiKey';
    const FORM_APIKEY = 'Api\DBBundle\Form\ApiKeyType';
    const ENTITY_FACEBOOK = 'ApiDBBundle:Facebook';
    const FORM_FACEBOOK = 'Api\DBBundle\Form\FacebookType';

    const ENTITY_DEVICE = 'ApiDBBundle:Device';
}