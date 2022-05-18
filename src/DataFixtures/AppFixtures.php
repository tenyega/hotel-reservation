<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Room;
use App\Entity\Payment;
use App\Entity\Customer;
use App\Entity\Reservation;
use Doctrine\ORM\Query\Expr\Math;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker)); //added it for the pictures 



        for ($c = 0; $c < (mt_rand(10, 30)); $c++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail("customer$c@gmail.com")
                ->setPhone($faker->phoneNumber())
                ->setAddress($faker->address());

            $this->em->persist($customer);




            $room = new Room;
            $room->setFacilityPossible('fer à repasser, minibar, internet, coffre fort,tele')
                ->setFloor(mt_rand(2, 10))
                ->setIsSmoking($faker->boolean(70))
                ->setMaxCapacity(mt_rand(2, 10))
                ->setPrice($faker->price(3000, 500))
                ->setRoomNo(mt_rand(20, 80))
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


            $reservation = new Reservation;
            $reservation->setCustomerID($customer)
                ->setBookingDate($faker->dateTimeBetween('-6 months'))
                ->setCheckInDate($faker->dateTimeBetween('-7 days', '+2 months'))
                ->setCheckOutDate($faker->dateTimeInInterval($reservation->getCheckInDate(), '+4days'))
                ->setNoAdult(mt_rand(1, 3))
                ->setNoEnfant(mt_rand(0, 3))
                ->setRoomNo($room->getRoomNo())
                ->setCodePromo(Reservation::CODE_PROMO)
                ->setSpecialDemande("nothing special")
                ->setStatus(Reservation::STATUS_PENDING);

            $this->em->persist($reservation);

            $payment = new Payment;
            $payment->setReservationID($reservation)
                ->setTotalCharges(mt_rand(1000, 25000));
            if ($faker->boolean(70)) {
                $payment->setCouponPromo(Payment::Payment_Coupon)
                    ->setDescription("Congratulations")
                    ->setPaymentType("Credit Card");
            } else {
                $payment->setPaymentType("Cash")
                    ->setCouponPromo("No Coupon")
                    ->setDescription("Sorry with out PROMO");
            }
            $this->em->persist($payment);
        }

        $manager->flush();
    }
}
