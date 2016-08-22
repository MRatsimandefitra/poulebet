<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 18/08/2016
 * Time: 15:42
 */
namespace Api\CommonBundle\Command;

use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalApiCommand extends ContainerAwareCommand
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';

    protected function configure()
    {
        $this
            ->setName('goalapi:check')

            // the short description shown while running "php bin/console list"
            ->setDescription('Check every 2 minutes the json data from goal api by ywoume ;).')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getJson();
        $items = $data['items'];
        $output->writeln("Set data in database");
        $this->setDataInDatabase($items, $output);
        $output->writeln("Command was successsful");

    }

    private function setDataInDatabase($items, $output){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        foreach($items as $vItems){
            /**
             * SET CHAMPIONAT
             */
            $titleChamionat = $vItems['details']['contest']['competition']['title'];
            $championat = $em->getRepository(self::ENTITY_MATCH)->findChampionat($titleChamionat);

            if(!$championat[0]){
                $output->writeln("new championat -> set championat in databse");
                $championat = new Championat();
                $championat->setNomChampionat($titleChamionat);
                $em->persist($championat);
                $em->flush();
            }else{
                $output->writeln("This championat exist -> no action");
                $championat = $championat[0];
            }

            $mId = $vItems['id'];
            $mDate = \DateTime::createFromFormat('Y-m-d h:i', date('Y-m-d h:i', $vItems['timestamp_starts']));
            /**
             * Equipe
             */
            $mEquipeDomicile = $vItems['teams']['hosts']['fullname'];
            $mEquipeVisiteur = $vItems['teams']['guests']['fullname'];
            /**
             * Score
             */
            $mScore = $vItems['score'];
            $resultatDomicile = substr($vItems['score'], 0,1);
            $resultatVisiteur  = substr($vItems['score'], -1);
            /**
             * Status
             */
            $mStatus = $vItems['status'];

            $now = new \DateTime('now');
            $interval = $mDate->diff($now);
            $tempEcoule = 0;
            for($a = 0; $a <= $interval->h; $a++){
                $minutes = 60;
                if($a == 1){
                    $minutesRestant = $interval->i;
                    $tempEcoule =   $minutes + $minutesRestant;
                }else{
                    $tempEcoule = $tempEcoule + $minutes;
                }

                if($tempEcoule > 100){
                    $tempEcoule = 90;
                }
            }

            $match = $em->getRepository(self::ENTITY_MATCH)->findMatchById($mId);
            if(!$match){
                $output->writeln("This is new match withd id (create) : ".$mId);
                $match = new Matchs();
                $match->setId($mId);
            }else{
                $output->writeln("This is new match exist (update): ".$mId);
                $match = $match[0];
            }
            $match->setStatusMatch($mStatus);
            $match->setCheminLogoDomicile('logo');
            $match->setDateMatch($mDate);
            $match->setScore($mScore);
            $match->setResultatDomicile($resultatDomicile);
            $match->setResultatVisiteur($resultatVisiteur);
            $match->setEquipeDomicile($mEquipeDomicile);
            $match->setEquipeVisiteur($mEquipeVisiteur);
            $match->setTempsEcoules($tempEcoule);
            $match->setChampionat($championat);
            //$this->insert($match, array('success' => 'success' , 'error' => 'error'));
            $em->persist($match);
            $em->flush();

            $output->writeln("Mtach insert : " .$mId);
        }

    }


    private function getJson(){
        $json = '
        {
   "total":64,
   "first":25,
   "last":40,
   "timestamp_created":1279662638,
   "items":{
      "20094d2713b5d1bc753f11511b4cae2d":{
         "id":"20094d2713b5d1bc753f11511b4cae2d",
         "status":"finished",
         "timestamp_starts":1276956000,
         "teams":{
            "hosts":{
               "id":"gha_int",
               "name":"Ghana",
               "fullname":"Ghana"
            },
            "guests":{
               "id":"aus_int",
               "name":"Australia",
               "fullname":"Australia"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":2,
            "group_id":"D"
         },
         "score":"1 - 1",
         "events":[
            {
               "player":"Holman",
               "minute":11,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            },
            {
               "player":"Kewell",
               "minute":24,
               "team":"guests",
               "type":"red_card"
            },
            {
               "player":"Gyan",
               "minute":25,
               "team":"hosts",
               "score":"1 - 1",
               "type":"penalty"
            },
            {
               "player":"Addy",
               "minute":40,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Mensah",
               "minute":79,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Annan",
               "minute":84,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Moore",
               "minute":85,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Ayew A.",
               "minute":86,
               "team":"hosts",
               "type":"yellow_card"
            }
         ]
      },
      "c3d5e14556a1e098572bcb918d0dfd26":{
         "id":"c3d5e14556a1e098572bcb918d0dfd26",
         "status":"finished",
         "timestamp_starts":1276972200,
         "teams":{
            "hosts":{
               "id":"cam_int",
               "name":"Cameroon",
               "fullname":"Cameroon"
            },
            "guests":{
               "id":"den_int",
               "name":"Denmark",
               "fullname":"Denmark"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":2,
            "group_id":"E"
         },
         "score":"1 - 2",
         "events":[
            {
               "player":"Etoo",
               "minute":10,
               "team":"hosts",
               "score":"1 - 0",
               "type":"goal"
            },
            {
               "player":"Bendtner",
               "minute":33,
               "team":"guests",
               "score":"1 - 1",
               "type":"goal"
            },
            {
               "player":"Bassong",
               "minute":49,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Rommedahl",
               "minute":61,
               "team":"guests",
               "score":"1 - 2",
               "type":"goal"
            },
            {
               "player":"M Bia",
               "minute":75,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Kjaer",
               "minute":87,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Sorensen",
               "minute":86,
               "team":"guests",
               "type":"yellow_card"
            }
         ]
      },
      "3c6b9100fcc415d487f95706eb1c8e93":{
         "id":"3c6b9100fcc415d487f95706eb1c8e93",
         "status":"finished",
         "timestamp_starts":1277033400,
         "teams":{
            "hosts":{
               "id":"svk_int",
               "name":"Slovakia",
               "fullname":"Slovakia"
            },
            "guests":{
               "id":"par_int",
               "name":"Paraguay",
               "fullname":"Paraguay"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":2,
            "group_id":"F"
         },
         "score":"0 - 2",
         "events":[
            {
               "player":"Vera",
               "minute":27,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            },
            {
               "player":"Durica",
               "minute":42,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Vera",
               "minute":45,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Sestak",
               "minute":47,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Weiss",
               "minute":84,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Riveros",
               "minute":86,
               "team":"guests",
               "score":"0 - 2",
               "type":"goal"
            }
         ]
      },
      "3956fa85a5dd600d33cf451fe8ae8fa5":{
         "id":"3956fa85a5dd600d33cf451fe8ae8fa5",
         "status":"finished",
         "timestamp_starts":1277042400,
         "teams":{
            "hosts":{
               "id":"ita_int",
               "name":"Italy",
               "fullname":"Italy"
            },
            "guests":{
               "id":"nzea_int",
               "name":"New Zealand",
               "fullname":"New Zealand"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":2,
            "group_id":"F"
         },
         "score":"1 - 1",
         "events":[
            {
               "player":"Smeltz",
               "minute":7,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            },
            {
               "player":"Fallon",
               "minute":14,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Smith",
               "minute":28,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Iaquinta",
               "minute":29,
               "team":"hosts",
               "score":"1 - 1",
               "type":"penalty"
            },
            {
               "player":"Nelsen",
               "minute":87,
               "team":"guests",
               "type":"red_card"
            }
         ]
      },

      "7ba64ba732e2dbd8e7759c4c67a4c962":{
         "id":"7ba64ba732e2dbd8e7759c4c67a4c962",
         "status":"finished",
         "timestamp_starts":1277119800,
         "teams":{
            "hosts":{
               "id":"por_int",
               "name":"Portugal",
               "fullname":"Portugal"
            },
            "guests":{
               "id":"kordpr_int",
               "name":"North Korea",
               "fullname":"Korea DPR"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":2,
            "group_id":"G"
         },
         "score":"7 - 0",
         "events":[
            {
               "player":"Meireles",
               "minute":29,
               "team":"hosts",
               "score":"1 - 0",
               "type":"goal"
            },
            {
               "player":"Pak C-J.",
               "minute":32,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Mendes",
               "minute":38,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Hong Y-J.",
               "minute":48,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Simao",
               "minute":53,
               "team":"hosts",
               "score":"2 - 0",
               "type":"goal"
            },
            {
               "player":"Almeida",
               "minute":56,
               "team":"hosts",
               "score":"3 - 0",
               "type":"goal"
            },
            {
               "player":"Tiago",
               "minute":60,
               "team":"hosts",
               "score":"4 - 0",
               "type":"goal"
            },
            {
               "player":"Almeida",
               "minute":70,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Liedson",
               "minute":81,
               "team":"hosts",
               "score":"5 - 0",
               "type":"goal"
            },
            {
               "player":"Ronaldo",
               "minute":87,
               "team":"hosts",
               "score":"6 - 0",
               "type":"goal"
            },
            {
               "player":"Tiago",
               "minute":89,
               "team":"hosts",
               "score":"7 - 0",
               "type":"goal"
            }
         ]
      },
      "91b2f1fe7d34bcc854f23a36462e724d":{
         "id":"91b2f1fe7d34bcc854f23a36462e724d",
         "status":"finished",
         "timestamp_starts":1277128800,
         "teams":{
            "hosts":{
               "id":"chil_int",
               "name":"Chile",
               "fullname":"Chile"
            },
            "guests":{
               "id":"swi_int",
               "name":"Switzerland",
               "fullname":"Switzerland"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":2,
            "group_id":"H"
         },
         "score":"1 - 0",
         "events":[
            {
               "player":"Suazo",
               "minute":2,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Nkufo",
               "minute":18,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Carmona",
               "minute":22,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Ponce",
               "minute":25,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Behrami",
               "minute":31,
               "team":"guests",
               "type":"red_card"
            },
            {
               "player":"Barnetta",
               "minute":48,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Fernandez",
               "minute":60,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Inler",
               "minute":60,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Medel",
               "minute":61,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Gonzalez",
               "minute":75,
               "team":"hosts",
               "score":"1 - 0",
               "type":"goal"
            },
            {
               "player":"Valdivia",
               "minute":90,
               "team":"hosts",
               "type":"yellow_card"
            }
         ]
      },
      "16aad7bccec575f6288d11a0549704db":{
         "id":"16aad7bccec575f6288d11a0549704db",
         "status":"finished",
         "timestamp_starts":1277145000,
         "teams":{
            "hosts":{
               "id":"spa_int",
               "name":"Spain",
               "fullname":"Spain"
            },
            "guests":{
               "id":"hond_int",
               "name":"Honduras",
               "fullname":"Honduras"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":2,
            "group_id":"H"
         },
         "score":"2 - 0",
         "events":[
            {
               "player":"Turcios",
               "minute":8,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Villa",
               "minute":17,
               "team":"hosts",
               "score":"1 - 0",
               "type":"goal"
            },
            {
               "player":"Izaguirre",
               "minute":38,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Villa",
               "minute":51,
               "team":"hosts",
               "score":"2 - 0",
               "type":"goal"
            }
         ]
      },
      "a24e98dec52e0e755a6457c78e8f7be9":{
         "id":"a24e98dec52e0e755a6457c78e8f7be9",
         "status":"finished",
         "timestamp_starts":1277215200,
         "teams":{
            "hosts":{
               "id":"fra_int",
               "name":"France",
               "fullname":"France"
            },
            "guests":{
               "id":"sar_int",
               "name":"South Africa",
               "fullname":"South Africa"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"A"
         },
         "score":"1 - 2",
         "events":[
            {
               "player":"Khumalo",
               "minute":20,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            },
            {
               "player":"Gourcuff",
               "minute":25,
               "team":"hosts",
               "type":"red_card"
            },
            {
               "player":"Mphela",
               "minute":37,
               "team":"guests",
               "score":"0 - 2",
               "type":"goal"
            },
            {
               "player":"Malouda",
               "minute":70,
               "team":"hosts",
               "score":"1 - 2",
               "type":"goal"
            },
            {
               "player":"Diaby",
               "minute":71,
               "team":"hosts",
               "type":"yellow_card"
            }
         ]
      },
      "bb8c5e185511872ddf00c272d9ddf5fc":{
         "id":"bb8c5e185511872ddf00c272d9ddf5fc",
         "status":"finished",
         "timestamp_starts":1277215200,
         "teams":{
            "hosts":{
               "id":"mex_int",
               "name":"Mexico",
               "fullname":"Mexico"
            },
            "guests":{
               "id":"uru_int",
               "name":"Uruguay",
               "fullname":"Uruguay"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"A"
         },
         "score":"0 - 1",
         "events":[
            {
               "player":"Suarez",
               "minute":43,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            },
            {
               "player":"Fucile",
               "minute":68,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Hernandez",
               "minute":77,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Castro",
               "minute":86,
               "team":"hosts",
               "type":"yellow_card"
            }
         ]
      },
      "2c245b0f2b2a862e558e8d886934307f":{
         "id":"2c245b0f2b2a862e558e8d886934307f",
         "status":"finished",
         "timestamp_starts":1277231400,
         "teams":{
            "hosts":{
               "id":"gre_int",
               "name":"Greece",
               "fullname":"Greece"
            },
            "guests":{
               "id":"arg_int",
               "name":"Argentina",
               "fullname":"Argentina"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"B"
         },
         "score":"0 - 2",
         "events":[
            {
               "player":"Katsouranis",
               "minute":30,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Bolatti",
               "minute":76,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Demichelis",
               "minute":77,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            },
            {
               "player":"Palermo",
               "minute":89,
               "team":"guests",
               "score":"0 - 2",
               "type":"goal"
            }
         ]
      },
      "4ed4709bc16116013f651d2aa1689ec6":{
         "id":"4ed4709bc16116013f651d2aa1689ec6",
         "status":"finished",
         "timestamp_starts":1277231400,
         "teams":{
            "hosts":{
               "id":"nig_int",
               "name":"Nigeria",
               "fullname":"Nigeria"
            },
            "guests":{
               "id":"korr_int",
               "name":"South Korea",
               "fullname":"Korea Republic"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"B"
         },
         "score":"2 - 2",
         "events":[
            {
               "player":"Uche",
               "minute":12,
               "team":"hosts",
               "score":"1 - 0",
               "type":"goal"
            },
            {
               "player":"Enyeama",
               "minute":31,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Obasi",
               "minute":37,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Lee J-S.",
               "minute":38,
               "team":"guests",
               "score":"1 - 1",
               "type":"goal"
            },
            {
               "player":"Ayila",
               "minute":42,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Park C-Y.",
               "minute":49,
               "team":"guests",
               "score":"1 - 2",
               "type":"goal"
            },
            {
               "player":"Namil",
               "minute":68,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Yakubu",
               "minute":69,
               "team":"hosts",
               "score":"2 - 2",
               "type":"penalty"
            }
         ]
      },
      "c709601a5b5dc9acc00daaf6ba040a58":{
         "id":"c709601a5b5dc9acc00daaf6ba040a58",
         "status":"finished",
         "timestamp_starts":1277301600,
         "teams":{
            "hosts":{
               "id":"svn_int",
               "name":"Slovenia",
               "fullname":"Slovenia"
            },
            "guests":{
               "id":"eng_int",
               "name":"England",
               "fullname":"England"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"C"
         },
         "score":"0 - 1",
         "events":[
            {
               "player":"Defoe",
               "minute":23,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            },
            {
               "player":"Jokic",
               "minute":40,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Johnson",
               "minute":48,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Birsa",
               "minute":79,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Dedic",
               "minute":81,
               "team":"hosts",
               "type":"yellow_card"
            }
         ]
      },
      "e63851b59c7ed2a5d56a9bc0d80e5ec9":{
         "id":"e63851b59c7ed2a5d56a9bc0d80e5ec9",
         "status":"finished",
         "timestamp_starts":1277301600,
         "teams":{
            "hosts":{
               "id":"usa_int",
               "name":"USA",
               "fullname":"USA"
            },
            "guests":{
               "id":"alg_int",
               "name":"Algeria",
               "fullname":"Algeria"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"C"
         },
         "score":"1 - 0",
         "events":[
            {
               "player":"Yebda",
               "minute":12,
               "team":"guests",
               "type":"yellow_card",
               "order":0
            },
            {
               "player":"Altidore",
               "minute":62,
               "team":"hosts",
               "type":"yellow_card",
               "order":0
            },
            {
               "player":"Yahia",
               "minute":76,
               "team":"guests",
               "type":"yellow_card",
               "order":0
            },
            {
               "player":"Lacen",
               "minute":83,
               "team":"guests",
               "type":"yellow_card",
               "order":0
            },
            {
               "player":"Beasley",
               "minute":90,
               "team":"hosts",
               "type":"yellow_card",
               "order":0
            },
            {
               "player":"Donovan",
               "minute":90,
               "team":"hosts",
               "score":"1 - 0",
               "type":"goal",
               "order":1
            },
            {
               "player":"Yahia",
               "minute":90,
               "team":"guests",
               "type":"red_card",
               "order":2
            }
         ]
      },
      "3a6f4dcedf43629bb7a67b5c905a75c7":{
         "id":"3a6f4dcedf43629bb7a67b5c905a75c7",
         "status":"finished",
         "timestamp_starts":1277317800,
         "teams":{
            "hosts":{
               "id":"aus_int",
               "name":"Australia",
               "fullname":"Australia"
            },
            "guests":{
               "id":"serb_int",
               "name":"Serbia",
               "fullname":"Serbia"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"D"
         },
         "score":"2 - 1",
         "events":[
            {
               "player":"Lukovic",
               "minute":18,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Beauchamp",
               "minute":49,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Wilkshire",
               "minute":50,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Ninkovic",
               "minute":59,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Emerton",
               "minute":67,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Cahill",
               "minute":69,
               "team":"hosts",
               "score":"1 - 0",
               "type":"goal"
            },
            {
               "player":"Holman",
               "minute":73,
               "team":"hosts",
               "score":"2 - 0",
               "type":"goal"
            },
            {
               "player":"Pantelic",
               "minute":84,
               "team":"guests",
               "score":"2 - 1",
               "type":"goal"
            }
         ]
      },
      "c3bee298213b5e15c633f032a37c2b48":{
         "id":"c3bee298213b5e15c633f032a37c2b48",
         "status":"finished",
         "timestamp_starts":1277317800,
         "teams":{
            "hosts":{
               "id":"gha_int",
               "name":"Ghana",
               "fullname":"Ghana"
            },
            "guests":{
               "id":"ger_int",
               "name":"Germany",
               "fullname":"Germany"
            }
         },
         "details":{
            "contest":{
               "competition":{
                  "id":"wc_2010",
                  "title":"wc_2010"
               }
            },
            "fixture_info":3,
            "group_id":"D"
         },
         "score":"0 - 1",
         "events":[
            {
               "player":"Ayew A.",
               "minute":40,
               "team":"hosts",
               "type":"yellow_card"
            },
            {
               "player":"Muller",
               "minute":43,
               "team":"guests",
               "type":"yellow_card"
            },
            {
               "player":"Ozil",
               "minute":60,
               "team":"guests",
               "score":"0 - 1",
               "type":"goal"
            }
         ]
      }
   }
}
        ';
        $data = json_decode($json, true);
        return $data;
    }
}