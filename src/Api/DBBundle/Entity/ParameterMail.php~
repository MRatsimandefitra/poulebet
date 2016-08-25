<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParameterMail
 *
 * @ORM\Table(name="parameter_mail")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ParameterMailRepository")
 */
class ParameterMail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="emailSite", type="string", length=255)
     */
    private $emailSite;

    /**
     * @var string
     *
     * @ORM\Column(name="nomExpediteur", type="string", length=255)
     */
    private $nomExpediteur;

    /**
     * @var string
     *
     * @ORM\Column(name="seuriteSMTP", type="string", length=255, nullable=true)
     */
    private $seuriteSMTP;

    /**
     * @var int
     *
     * @ORM\Column(name="portSMTP", type="integer", nullable=true)
     */
    private $portSMTP;

    /**
     * @var string
     *
     * @ORM\Column(name="userSMTP", type="string", length=255, nullable=true)
     */
    private $userSMTP;

    /**
     * @var string
     *
     * @ORM\Column(name="passwordSMTP", type="string", length=255, nullable=true)
     */
    private $passwordSMTP;

    /**
     * @var string
     *
     * @ORM\Column(name="serveurSMTP", type="string", length=255, nullable=true)
     */
    private $serveurSMTP;

    /**
     * @var \string
     *
     * @ORM\Column(name="adresse_societe", type="text", nullable=true)
     */
    private $adresse_societe;

    /**
     * @var string
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
     * @var \string
     *
     * @ORM\Column(name="subject_inscription", type="string", length=255, nullable=true)
     */
    private $subject_inscription;
    
     /**
     * @var \string
     *
     * @ORM\Column(name="template_inscription", type="text", nullable=true)
     */
    private $template_inscription;
    /**
     * @var \string
     *
     * @ORM\Column(name="subject_mdp_oublie", type="string", length=255, nullable=true)
     */
    private $subject_mdp_oublie;
    
     /**
     * @var \string
     *
     * @ORM\Column(name="template_mdp_oublie", type="text", nullable=true)
     */
    private $template_mdp_oublie;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set emailSite
     *
     * @param string $emailSite
     *
     * @return ParameterMail
     */
    public function setEmailSite($emailSite)
    {
        $this->emailSite = $emailSite;

        return $this;
    }

    /**
     * Get emailSite
     *
     * @return string
     */
    public function getEmailSite()
    {
        return $this->emailSite;
    }

    /**
     * Set nomExpediteur
     *
     * @param string $nomExpediteur
     *
     * @return ParameterMail
     */
    public function setNomExpediteur($nomExpediteur)
    {
        $this->nomExpediteur = $nomExpediteur;

        return $this;
    }

    /**
     * Get nomExpediteur
     *
     * @return string
     */
    public function getNomExpediteur()
    {
        return $this->nomExpediteur;
    }

    /**
     * Set seuriteSMTP
     *
     * @param string $seuriteSMTP
     *
     * @return ParameterMail
     */
    public function setSeuriteSMTP($seuriteSMTP)
    {
        $this->seuriteSMTP = $seuriteSMTP;

        return $this;
    }

    /**
     * Get seuriteSMTP
     *
     * @return string
     */
    public function getSeuriteSMTP()
    {
        return $this->seuriteSMTP;
    }

    /**
     * Set portSMTP
     *
     * @param integer $portSMTP
     *
     * @return ParameterMail
     */
    public function setPortSMTP($portSMTP)
    {
        $this->portSMTP = $portSMTP;

        return $this;
    }

    /**
     * Get portSMTP
     *
     * @return int
     */
    public function getPortSMTP()
    {
        return $this->portSMTP;
    }

    /**
     * Set userSMTP
     *
     * @param string $userSMTP
     *
     * @return ParameterMail
     */
    public function setUserSMTP($userSMTP)
    {
        $this->userSMTP = $userSMTP;

        return $this;
    }

    /**
     * Get userSMTP
     *
     * @return string
     */
    public function getUserSMTP()
    {
        return $this->userSMTP;
    }

    /**
     * Set passwordSMTP
     *
     * @param string $passwordSMTP
     *
     * @return ParameterMail
     */
    public function setPasswordSMTP($passwordSMTP)
    {
        $this->passwordSMTP = $passwordSMTP;

        return $this;
    }

    /**
     * Get passwordSMTP
     *
     * @return string
     */
    public function getPasswordSMTP()
    {
        return $this->passwordSMTP;
    }

    /**
     * Set serveurSMTP
     *
     * @param string $serveurSMTP
     *
     * @return ParameterMail
     */
    public function setServeurSMTP($serveurSMTP)
    {
        $this->serveurSMTP = $serveurSMTP;

        return $this;
    }

    /**
     * Get serveurSMTP
     *
     * @return string
     */
    public function getServeurSMTP()
    {
        return $this->serveurSMTP;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ParameterMail
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param string $updatedAt
     *
     * @return ParameterMail
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set adresseSociete
     *
     * @param string $adresseSociete
     *
     * @return ParameterMail
     */
    public function setAdresseSociete($adresseSociete)
    {
        $this->adresse_societe = $adresseSociete;

        return $this;
    }

    /**
     * Get adresseSociete
     *
     * @return string
     */
    public function getAdresseSociete()
    {
        return $this->adresse_societe;
    }

    /**
     * Set subjectInscription
     *
     * @param string $subjectInscription
     *
     * @return ParameterMail
     */
    public function setSubjectInscription($subjectInscription)
    {
        $this->subject_inscription = $subjectInscription;

        return $this;
    }

    /**
     * Get subjectInscription
     *
     * @return string
     */
    public function getSubjectInscription()
    {
        return $this->subject_inscription;
    }

    /**
     * Set templateInscription
     *
     * @param string $templateInscription
     *
     * @return ParameterMail
     */
    public function setTemplateInscription($templateInscription)
    {
        $this->template_inscription = $templateInscription;

        return $this;
    }

    /**
     * Get templateInscription
     *
     * @return string
     */
    public function getTemplateInscription()
    {
        return $this->template_inscription;
    }

    /**
     * Set subjectMdpOublie
     *
     * @param string $subjectMdpOublie
     *
     * @return ParameterMail
     */
    public function setSubjectMdpOublie($subjectMdpOublie)
    {
        $this->subject_mdp_oublie = $subjectMdpOublie;

        return $this;
    }

    /**
     * Get subjectMdpOublie
     *
     * @return string
     */
    public function getSubjectMdpOublie()
    {
        return $this->subject_mdp_oublie;
    }

    /**
     * Set templateMdpOublie
     *
     * @param string $templateMdpOublie
     *
     * @return ParameterMail
     */
    public function setTemplateMdpOublie($templateMdpOublie)
    {
        $this->template_mdp_oublie = $templateMdpOublie;

        return $this;
    }

    /**
     * Get templateMdpOublie
     *
     * @return string
     */
    public function getTemplateMdpOublie()
    {
        return $this->template_mdp_oublie;
    }
}
