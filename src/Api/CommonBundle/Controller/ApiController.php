<?php

namespace Api\CommonBundle\Controller;

use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller implements InterfaceDB
{

    protected function generateToken($userToken){
        return "".time()."_".$userToken."_".(time() + (1 * 24 * 60 * 60));
    }

    public function generateQueryBuilder($request)
    {


    }
    /**
     * @param $entity
     * @return mixed
     */
    public function getAllEntity($entity)
    {
        return $this->getEm()->getRepository($entity)->findAll();
    }

    /**
     * @return object
     */
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $object
     * @param null $msg
     */
    public function insert($object, $msg = array())
    {
        if (!$msg) {
            $msg = "";
        }
        if (method_exists($object, 'setCreatedAt')) {
            $object->setCreatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setCreatedBy')) {
            $user = $this->getUser();
            if (!$user) {
                $user = 'Anonymous';
            }
            if (is_object($user)) {
                $user = $user->getUsername();
            }
            $object->setCreatedBy($user);
        }
        if (method_exists($object, 'setUpdatedBy')) {
            $user = $this->getUser();
            if (!$user) {
                $user = 'Anonymous';
            }
            if (is_object($user)) {
                $user = $user->getUsername();
            }
            $object->setUpdatedBy($user);
        }

        try {
            $this->getEm()->persist($object);
            $this->getEm()->flush();
            //$this->addFlash("success", $this->get('translator')->trans($msg['success']));
            return true;
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $this->addFlash("error", "Ce compte existe déjà");
            return false;
        }


    }

    /**
     * @param $form
     * @param $entity
     * @return \Symfony\Component\Form\Form
     */
    public function formPost($form, $entity, $options = array())
    {
        if(empty($options)){

            return $this->createForm($form, $entity, array('method' => 'POST'));
        }
        if($options){

            $options['method'] = 'POST';
            return $this->createForm($form, $entity,$options);
        }

    }

    /**
     * @param $form
     * @param $entity
     * @return \Symfony\Component\Form\Form
     */
    public function formGet($form, $entity)
    {
        return $this->createForm($form, $entity, array('method' => 'GET'));
    }

    /**
     * @param $entity
     * @param $id
     * @return mixed
     */
    public function getRepoFormId($entity, $id)
    {
        return $this->getRepo($entity)->find($id);
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getRepo($entity)
    {
        return $this->getEm()->getRepository($entity);
    }

    public function getObjectRepoFrom($entity, $params = array())
    {
        return $this->getRepo($entity)->findOneBy($params);
    }
    /**
     * @param $entity
     * @param array $params
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @return mixed
     */
    public function getRepoFrom($entity, $params = array(), array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepo($entity)->findBy($params, $orderBy, $limit, $offset);
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getAllRepo($entity)
    {
        return $this->getRepo($entity)->findAll();
    }

    public function remove($entity)
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();
    }

    /*public function formValidate($form, $entity, $request){
        $form = $this->formPost($form, $entity);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($entity);
            return $form;
        }else{
            die('plpl');
            return false;
        }
    }*/
    public function getMsg()
    {
        return array(
            'success' => 'success',
            'error' => 'Error'
        );
    }

    public function sqlWay()
    {
        return $this->get('database_connection');
    }

    public function uploadFile($fileName, $file, $directory, $isForSystem = true, $isFront = null)
    {

        if ($isForSystem) {
            $uploadDirTmp = $this->container->getParameter('kernel.root_dir') . '/../src/Api/ThemeBundle/Resources/public/img/system/';
        } else {
            if ($isFront) {
                $uploadDirTmp = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/Front/';
            } else {
                $uploadDirTmp = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/';
            }

        }

        // Move the file to the directory where brochures are stored

        $fileDir = $uploadDirTmp . $directory . '/';

        if (!file_exists($fileDir)) {

            mkdir($fileDir, 0777, true);

        }
        $file->move($fileDir, $fileName);
        //$this->installAssets();

        return $fileName;
    }

    public function installAssets()
    {

        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $ap = $application->getKernel()->getRootDir() . '../';
        //var_dump($application->getKernel()->getRootDir().'../web'); die;
        $input = new ArrayInput(array(
            'command' => 'assets:install',
        ));
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->container->get('security.token_storage')->getToken()->getRoles();
    }

    /**
     * @param $role
     * @return bool
     */
    public function testRoles($role)
    {
        $cRole = $this->getRoles();
        if (trim($cRole) == trim($role)) {
            return true;
        }
        return false;
    }


    public function translate($text)
    {
        return $this->container->get('translator')->trans($text);
    }

    public function formProcess($entity, $form, $request, $success, $error)
    {

        $form = $this->formPost($form, $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->insert($entity, array(
                'success' => $this->translate($success),
                'error' => $this->translate($error)
            ));
        }
        return $form;
    }
    protected function encodePassword($password){
        return md5($password);
    }
    protected function sendGCMNotification($data){
        $http = $this->get('http');
        // chargement des paramètres de gcm
        $apikey = $this->getParameter("apikey");
        $gcm_url_android = $this->getParameter("gcm_url_android");
        $header = 'Authorization: key='.$apikey;
        $http->setUrl($gcm_url_android);
        $http->setHeaders($header);
        $http->setRawPostData(json_encode($data));
        $response = $http->execute();
        return $response;
    }
    
    /**
     * Check if param sending is valid
     * 
     * @param type $value
     * @param type $entityClass entity class when param is entity (id)
     * @param type $param field to check (default ID)
     * @return boolean|object if not valid, you must check in the condition with operator $returnValue === false
     */
    protected function checkParamWs($value,$entityClass = '',$param = 'id'){
        if(empty($value)){
            return false;
        }
        if(empty($entityClass)){
            return true;
        }
        //check if entity exist
        $entity = $this->getObjectRepoFrom($entityClass,array($param => $value));
        if(!$entity){
            return false;
        }
        return $entity;
    }
    
    /**
     * Check if user can buy
     * 
     * @param Utilisateur $user
     * @param int $needle
     * @return boolean|object if not valid, you must check in the condition with operator $returnValue === false
     */
    protected function checkIfUserCanBuy($user,$needle){
        // last Solde
        $credit = $this->getRepo('ApiDBBundle:MvtCredit')->getLastByUser($user);
        $dernierSolde = 0;
        if ($credit) {
            $dernierSolde = $credit->getSoldeCredit();
        }
        if($dernierSolde >= $needle){
            return $dernierSolde;
        }
        return false;
    }    
    
    /**
     * Output json error message
     * 
     * @param string $message
     * @return JsonResponse
     */
    protected function sendJsonErrorMsg($message){
        $out['code_error'] = 2;
        $out['error'] = true;
        $out['success'] = false;
        $out['message'] = $message;
        return new JsonResponse($out);
    }
    
    /**
     * Send email
     * 
     * @param Utilisateur $user
     * @param string $subject
     * @param string $message
     * @param ParameterMail $parameter
     */
    protected function sendMail($user,$subject,$message,$parameter){
        $mailerService = $this->get('mail.manager');
        $mailerService->setSubject($subject);
        $mailerService->setFrom($parameter->getEmailSite());
        $mailerService->setTo($user->getEmail());
        $mailerService->addParams('body',$message);
        return $mailerService->send();
    }


    protected function isExpiredToken($token){
        $res = split('_', $token);
        $time = intval($res[2]);
        if ($time < time()){
            return true;
        }
        return false;
    }
    protected function generatePassword($length = 5){
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);

    }
    protected function sendEmail($message){
        $res = $this->get('mailer')->send($message);
        return true;
    }
    protected function getMailerService(){
        return $this->get('mail.manager');
    }
    protected function getParameterMail() {
        $params = $this->getEm()->getRepository(self::ENTITY_PARAMETER_MAIL)->findAll();
        if($params){
            return $params[count($params)-1];
        }
        return false;
    }
}
