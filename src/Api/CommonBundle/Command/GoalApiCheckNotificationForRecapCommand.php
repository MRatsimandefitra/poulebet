<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 18/08/2016
 * Time: 15:42
 */
namespace Api\CommonBundle\Command;

use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiCheckNotificationForRecapCommand extends ContainerAwareCommand implements InterfaceDB
{


    protected function configure()
    {
        $this
            ->setName('goalapi:check:notification-recap')
            // the short description shown while running "php bin/console list"
            ->setDescription('check new notifiation for recapitulation.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $notifRecap = $em->getRepository(self::ENTITY_NOTIF_RECAP)->findNoSended();
        if(!$notifRecap){
            $output->writeln("No notification");
        }
        foreach($notifRecap as $kNotifRecap => $itemsNotifRecap){
            var_dump($itemsNotifRecap); die;
        }
        $output->writeln("Command was successfull");
    }


}