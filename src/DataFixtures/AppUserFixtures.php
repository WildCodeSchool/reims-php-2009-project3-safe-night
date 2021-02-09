<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppUserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $bruno = new User();
        $bruno->setFirstName('Bruno');
        $bruno->setLastname('Lagasse');
        $bruno->setBirthday(new DateTime('04-12-1997'));
        $bruno->setAddress('26 rue Thiers, 51100 Reims');
        $bruno->setEmail('bruno@gmail.com');
        $encodedPassword = $this->encoder->encodePassword($bruno, '123456');
        $bruno->setPassword($encodedPassword);
        $bruno->setPhoneNumber('06 85 75 96 25');
        $bruno->setAvatar('Bruno-600ad2678d7cd.jpeg');
        $manager->persist($bruno);

        $ilyes = new User();
        $ilyes->setFirstName('Ilyes');
        $ilyes->setLastname('Rahim');
        $ilyes->setBirthday(new DateTime('10-06-1999'));
        $ilyes->setAddress('64 Boulevard Henry Vasnier, 51100 Reims');
        $ilyes->setEmail('ilyes@gmail.com');
        $encodedPassword = $this->encoder->encodePassword($ilyes, '123456');
        $ilyes->setPassword($encodedPassword);
        $ilyes->setPhoneNumber('06 13 68 45 01');
        $ilyes->setAvatar('IMG_20200831_214845_145.jpg');
        $manager->persist($ilyes);

        $baptiste = new User();
        $baptiste->setFirstName('Baptiste');
        $baptiste->setLastname('Vayssié');
        $baptiste->setBirthday(new DateTime('18-03-1989'));
        $baptiste->setAddress('3 rue Barbara, 51450 Betheny');
        $baptiste->setEmail('baptiste@gmail.com');
        $encodedPassword = $this->encoder->encodePassword($baptiste, '123456');
        $baptiste->setPassword($encodedPassword);
        $baptiste->setPhoneNumber('06 95 86 25 13');
        $baptiste->setAvatar('450.png');
        $manager->persist($baptiste);

        $axel = new User();
        $axel->setFirstName('Axel');
        $axel->setLastname('Croizé');
        $axel->setBirthday(new DateTime('16-10-1996'));
        $axel->setAddress('123 boulevard Charles Arnould, 51100 Reims');
        $axel->setEmail('axel@gmail.com');
        $encodedPassword = $this->encoder->encodePassword($axel, '123456');
        $axel->setPassword($encodedPassword);
        $axel->setPhoneNumber('06 95 86 25 13');
        $axel->setAvatar('71502055-10212273851962068-4228461518422802432-o-5ffdc95161e4b.jpeg');
        $manager->persist($axel);

        $linus = new User();
        $linus->setFirstName('Linus');
        $linus->setLastname('Torvalds');
        $linus->setBirthday(new DateTime('28-12-1969'));
        $linus->setAddress('49 Avenue du Général de Gaulle, 51100 Reims');
        $linus->setEmail('linus@gmail.com');
        $encodedPassword = $this->encoder->encodePassword($linus, '123456');
        $linus->setPassword($encodedPassword);
        $linus->setPhoneNumber('06 01 85 45 67');
        $linus->setAvatar('linus-5ffdc3b6df085.png');
        $manager->persist($linus);

        $caribou = new User();
        $caribou->setFirstName('Axel');
        $caribou->setLastname('Raboit');
        $caribou->setBirthday(new DateTime('08-11-1991'));
        $caribou->setAddress('3 Rue Maurice Cerveaux, 51200 Épernay');
        $caribou->setEmail('caribou@gmail.com');
        $encodedPassword = $this->encoder->encodePassword($caribou, '123456');
        $caribou->setPassword($encodedPassword);
        $caribou->setPhoneNumber('06 85 14 26 84');
        $caribou->setAvatar('thriller-5ff6e02b0b0b7.webp');
        $manager->persist($caribou);

        $cedric = new User();
        $cedric->setFirstName('Cédric');
        $cedric->setLastname('Dupont');
        $cedric->setBirthday(new DateTime('10-09-1987'));
        $cedric->setAddress('17 Rue Maucroix, 51100 Reims');
        $cedric->setEmail('cedric.dupont@gmail.com');
        $encodedPassword = $this->encoder->encodePassword($cedric, '123456');
        $cedric->setPassword($encodedPassword);
        $cedric->setPhoneNumber('06 19 38 72 77');
        $cedric->setAvatar('cedric.webp');
        $cedric->addFriend($caribou);
        $cedric->addFriend($linus);
        $cedric->addFriend($axel);
        $cedric->addFriend($baptiste);
        $cedric->addFriend($ilyes);
        $cedric->addFriend($bruno);
        $manager->persist($cedric);
        $this->addReference('cedric', $cedric);

        $manager->flush();
    }
}
