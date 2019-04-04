<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Trick;
use App\Entity\category;
use App\Entity\Image;
use App\Service\VideoLinkValidator;

class TrickFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $videoLinkvalidator = new VideoLinkValidator();
        //create category
        $airCat = new category();
        $slideCat = new Category();

        $slideCat->setName('slide');
        $airCat->setName('air');
        $manager->persist($airCat);
        $manager->persist($slideCat);
        $manager->flush();
        //create trickList
        $trickList = [
            ['name' => 'Backside Air', 'desc' => 'On commence tout simplement avec LE trick. Les mauvaises langues prétendent qu’un backside air suffit à reconnaître ceux qui savent snowboarder. Si c’est vrai, alors Nicolas Müller est le meilleur snowboardeur du monde. Personne ne sait s’étirer aussi joliment, ne demeure aussi zen, n’est aussi provocant dans la jouissance.', 'videoList' => ['https://www.youtube.com/watch?v=h0UtyOX9p90']],
            ['name' => 'One Foot', 'desc' => 'Bode Merril est la preuve vivante que la réincarnation n’est pas un conte de fée. Dans sa vie antérieure de flamant rose, il avait déjà l’habitude d’affronter le quotidien sur une patte. Quelque 200 ans plus tard, il a eu la chance d’être un homme doté d’un snowboard, ce qui a fini par donner à son être l’élan nécessaire. Il aime bien s’avaler quelques one foot double backflips au p’tit déj.', 'videoList' => ['https://www.youtube.com/watch?v=yK5GFfqeYfU']],
            ['name' => 'Switch Backside Rodeo 720', 'desc' => 'Si l’univers du snowboard a jamais disposé d’un scientifique, alors c’était David Benedek. Personne mieux que lui n’a su comment monter un kicker pour qu’un trick marche bien. En musique, on qualifie cela d’expérimental. Ce n’est pas un hasard si c’est précisément lui qui a eu l’idée de faire un switch BS rodeo.', 'videoList' => ['https://www.youtube.com/watch?v=BH42KlQ0QsE']],
            ['name' => 'BS 540 Seatbelt', 'desc' => 'Hitsch aurait tout aussi bien pu faire de la danse classique mais il s’est décidé pour la neige. Peut-être tout simplement parce qu’en Engadine, les montagnes sont plus séduisantes que les gymnases. Quoi qu’il en soit, quiconque arrive à attraper aussi tranquillement l’arrière de la planche avec la main avant pendant un BS 5 dans un half-pipe sans s’ouvrir les lèvres sur le coping devrait occuper une chaire à Cambridge sur les prodiges de la coordination.', 'videoList' => ['https://www.youtube.com/watch?v=nwic0F9CwI0']],
            ['name' => 'FS 720 Japan', 'desc' => 'Si, dans le monde du snowboard, il y avait aujourd’hui encore quelque chose de comparable au rock’n’roll, Ben Ferguson en serait le Jim Morrison, haut la main. Son riding est radical, sans compromis et beau à voir. Bien entendu, rien ne se fait à moins de 200 km/h, pas même les FS 7 Japan dans le pipe.', 'videoList' => ['https://www.youtube.com/watch?v=nwic0F9CwI0']],
            ['name' => 'Skate Skills', 'desc' => 'Scott «MacGyver» Stevens n’aurait en fait pas besoin de forfait de remontée. Scott n’aurait même pas besoin d’aller à la montagne. Scott a juste à sortir de chez lui, respirer un bon coup et démarrer. Après trois jours de tournage, son rôle serait plus long et plus créatif que pour ceux qui ont dû passer 20 heures en avion, 10 heures en voiture, 5 heures en Ski-Doo et 2 heures en hélicoptère pour leur séquence.', 'videoList' => ['https://www.youtube.com/watch?v=2RlDSbxsnyc']],
            ['name' => 'Switch Method', 'desc' => 'Danny Davis est comme ces meilleurs potes qui peuvent faire tous les tricks avec juste un tout petit plus de classe que toi. Aussi difficiles ou aussi faciles soient-ils. Si un nombre incalculable de blessures ne l’avait pas cloué au lit, il aurait mis sens dessus dessous le monde de la compétition en pipe. Heureusement qu’il y a la vidéo. Et puis on peut quand même se faire une compétition de temps en temps. Et alors on peut y mettre un peu de switch method pour faire tomber les juges à la renverse.', 'videoList' => ['https://www.youtube.com/watch?v=Ur1Nrz23sSI']],
            ['name' => 'Going bigger', 'desc' => 'Soyons francs, tous les tricks de Travis pourraient être des signatures. Son genre «I go bigger» se voit probablement dès le matin aux toilettes. Trois fois par dessus la tête ou simply straight, il semble que Travis puisse à peu près tout essayer plus haut, plus loin, plus extrême, plus beau et en premier la plupart du temps.', 'videoList' => ['https://www.youtube.com/watch?v=wlEY-D2F6Yk']],
            ['name' => 'McTwist', 'desc' => 'Terje se sent mieux dans les transitions que Trump dans sa tour. Pas étonnant, il a détenu pendant longtemps le record du highest air. En mars 2007 à Oslo, il s’est catapulté à un bon 9,8 mètres sur un quarterpipe. Pendant le saut, l’altitude l’a surpris lui-même, c’est pourquoi il a rapidement transformé la méthode prévue en un BS 360. Pourquoi se priver quand on peut. Les McTwists dans un half-pipe par contre c’est plutôt comme une fête d’anniversaire. On a besoin d’un peu d’alley-oop et de chicken wings pour trouver le frisson.', 'videoList' => ['https://www.youtube.com/watch?v=viznc0h6BQg']],
            ['name' => 'Buttered Tricks', 'desc' => 'Que faire quand passer les kickers devient monotone? Facile, on rend l’approche plus difficile. C’est du moins ce que s’est dit Jussi quand il a filmé son rôle pour Afterbang (Robot Food; 2002). Concrètement, ça veut dire faire du buttering à gogo. Pour Jussi, ce n’est pas vraiment un problème même avant un switch backside 900.', 'videoList' => ['https://www.youtube.com/watch?v=CDmW1yvY1nM']],
            ['name' => 'Lobster Flip', 'desc' => 'Nommer son trick typique d’après sa propre marque de snowboard est plutôt osé. Les mômes regardent la vidéo, se disent «ouaouh», essaient d’imiter l’acrobatie et avant ça vont s’acheter la planche qu’il faut. D’apparence innocent comme un agneau, Halldor est en fait le businessman le plus dur à cuire d’Islande. Tout cela sans le vouloir évidemment.', 'videoList' => ['https://www.youtube.com/watch?v=q-RAJnV1dfg']],
            ['name' => 'Nuckle Tricks', 'desc' => 'Marcus est né juste avant le millénaire et atteint sa majorité cette année. Quel toupet quand on pense à tous les tricks que ce gamin a déjà sous la ceinture. Qu’est-ce que vont dire ses petits enfants en 2075 quand il leur racontera qu’il a appris à faire ses premier 1080 en atterrissant des kickers? Et qu’est-ce qu’il pourra bien leur raconter sur les 2022? Backside Octa Cork to FS Edgeslide au-dessus de télécabine to Triple FS Rodeo Truck Driver out?', 'videoList' => ['https://www.youtube.com/watch?v=tIW5dLjv3CM']],
            ['name' => 'FS 900', 'desc' => 'Quand le style est vraiment original, on le reconnaît même dans les tricks banals. Vous en voulez l’exemple parfait? Travis Parker. Il fait un FS 900 (aujourd’hui aussi banal que l’était l’indy il y a 20 ans) depuis la carre front, tient le nose et pendant un instant le monde s’immobilise. Que Travis soit original est de toute manière indiscutable. Qui d’autre annule du jour au lendemain les contrats avec tous ses sponsors pour devenir cuisinier de sushis?', 'videoList' => ['https://www.youtube.com/watch?v=IPc7cJHt1rc']],
        ];

        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');
        $i = 0;
        while (count($trickList) > $i) {
            $trick = new Trick();
            $trick->setName($trickList[$i]['name']);
            $trick->setCategory($airCat);
            $trick->setDescription($trickList[$i]['desc']);
            $trick->setVideoList($videoLinkvalidator->checkUrl($trickList[$i]['videoList']));
            $image = new Image();
            $image->setName('default'.$i.'.jpg');
            $trick->addImageList($image);
            $trick->setCreatedAt($date);
            $trick->setEdited('no');
            $trick->setEditedAt($date);
            ++$i;
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
