<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Room;
use App\Entity\User;
use App\Entity\Reservation;
use Doctrine\ORM\Query\Expr\Math;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    protected $em;
    protected $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker)); //added it for the pictures 
        $roomNo = 1;
        $total = 0;
        $users = [];

        $admin = new User;
        $hashedPassword = $this->encoder->hashPassword($admin, "admin");
        $admin->setEmail("admin@gmail.com")
            ->setPassword($hashedPassword)
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Monsieur")
            ->setLastName('Admin')
            ->setAddress("10 rue de la formateur, paris 75015")
            ->setPhone('123456')
            ->setIsConfirmed(true);
        $this->em->persist($admin);
        $this->em->flush();


        for ($c = 0; $c < (mt_rand(10, 30)); $c++) {
            $user = new User();
            $hashedPassword = $this->encoder->hashPassword($user, "user");
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail("user$c@gmail.com")
                ->setPhone($faker->phoneNumber())
                ->setAddress($faker->address())
                ->setPassword($hashedPassword)
                ->setIsConfirmed($faker->boolean(70));
            $users[] = $user;
            $this->em->persist($user);




            $room = new Room;
            $room->setFacilityPossible('fer à repasser, minibar, internet, coffre fort,tele')
                ->setFloor(mt_rand(2, 10))
                ->setIsSmoking($faker->boolean(70))
                ->setMaxCapacity(mt_rand(2, 10))
                ->setPrice($faker->price(3000, 500))
                ->setRoomNo($roomNo)
                ->setDescription("Nos chambres Deluxe, raffinées et lumineuses, représentent l’atmosphère du quartier. Spacieuses, vous profiterez d’un espace bureau, d’une salle de bain avec baignoire ou douche, et d’une literie au choix (double ou lits jumeaux). Nos chambres sont insonorisées pour un séjour toute en tranquillité.")
                ->setMainPicture(Room::ROOM_IMAGE1)
                ->setOtherPicture(Room::ROOM_IMAGE2)
                ->setAnotherpicture($faker->imageUrl(400, 400));
            if ($faker->boolean(70)) {
                $room->setType(Room::TYPE_AC)
                    ->setBedding(Room::BED_DOUBLE);
            } else {
                $room->setType(Room::TYPE_NONAC)
                    ->setBedding(Room::BED_SIMPLE);
            }

            $this->em->persist($room);


            $noOfDays = 0;
            $reservation = new Reservation;
            $reservation->setUserID($user)
                ->setBookingDate($faker->dateTimeBetween('-6 months'))
                ->setCheckInDate($faker->dateTimeBetween('-7 days', '+2 months'))
                ->setCheckOutDate($faker->dateTimeInInterval($reservation->getCheckInDate(), '+4days'))
                ->setNoAdult(mt_rand(1, 3))
                ->setNoEnfant(mt_rand(0, 3))
                ->setRoomNo($roomNo) // to get the room no which we have just worked 
                ->setCodePromo(Reservation::CODE_PROMO)
                ->setSpecialDemande("nothing special");
            if ($faker->boolean(98)) {
                $reservation->setStatus(Reservation::STATUS_PAID);
            } else {
                $reservation->setStatus(Reservation::STATUS_PENDING);
            }


            $checkIn = "";
            $checkOut = "";
            $checkIn = new DateTime($reservation->getCheckInDate()->format('Y-m-d'));
            $checkOut = new DateTime($reservation->getCheckOutDate()->format('Y-m-d'));
            if ($checkIn === $checkOut) {
                $noOfDays = 1;
            } else {

                $noOfDays = ($checkIn->diff($checkOut))->format("%a");
            }
            $totalNoOfDays = ($noOfDays + 1);


            $total = ($totalNoOfDays * $room->getPrice()) * 100;
            //dump($total);
            $reservation->setTotal($total);

            $this->em->persist($reservation);

            //////////////////////////////////////////////////////////////////////////////////// Another Reservation for same room and same user 
            $noOfDays = 0;
            $reservation1 = new Reservation;
            $reservation1->setUserID($faker->randomElement($users))
                ->setBookingDate($faker->dateTimeBetween('-6 months'))
                ->setCheckInDate($faker->dateTimeInInterval($reservation->getCheckOutDate(), '+30days'))
                ->setCheckOutDate($faker->dateTimeInInterval($reservation1->getCheckInDate(), '+4days'))
                ->setNoAdult(mt_rand(1, 3))
                ->setNoEnfant(mt_rand(0, 3))
                ->setRoomNo($roomNo) // to get the room no which we have just worked 
                ->setCodePromo(Reservation::CODE_PROMO)
                ->setSpecialDemande("nothing special")
                ->setStatus(Reservation::STATUS_PENDING);

            $checkIn = "";
            $checkOut = "";
            $checkIn = new DateTime($reservation1->getCheckInDate()->format('Y-m-d'));
            $checkOut = new DateTime($reservation1->getCheckOutDate()->format('Y-m-d'));
            if ($checkIn === $checkOut) {
                $noOfDays = 1;
            } else {

                $noOfDays = ($checkIn->diff($checkOut))->format("%a");
            }
            $totalNoOfDays = ($noOfDays + 1);


            $total1 = ($totalNoOfDays * $room->getPrice()) * 100;
            //dump($total);
            $reservation1->setTotal($total1);

            $this->em->persist($reservation1);



            $roomNo = $roomNo + 1; // just to have unique room no everytime 
        }

        $manager->flush();
    }
}
