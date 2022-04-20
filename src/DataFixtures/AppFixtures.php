<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\Room;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Math;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

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
        for ($c = 0; $c < (mt_rand(10, 30)); $c++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail("customer$c@gmail.com")
                ->setPhone($faker->phoneNumber())
                ->setAddress($faker->address());

            $this->em->persist($customer);

            $reservation = new Reservation;
            $reservation->setCustomerID($customer)
                ->setBookingDate($faker->dateTimeBetween('-6 months'))
                ->setCheckInDate($faker->dateTimeBetween('-6 months'))
                ->setCheckOutDate($faker->dateTimeInInterval($reservation->getCheckInDate(), '+4days'))
                ->setNumberOfBeds(mt_rand(2, 5))
                ->setRoomNo(mt_rand(1, 15))
                ->setSpecialDemande("nothing special");

            $this->em->persist($reservation);



            $room = new Room;
            $room->setFacilityPossible('Iron, TV')
                ->setFloor(mt_rand(2, 10))
                ->setIsSmoking($faker->boolean(70))
                ->setMaxCapacity(mt_rand(2, 10))
                ->setRoomNo(mt_rand(20, 80));
            if ($faker->boolean(70)) {
                $room->setType(Room::TYPE_AC);
            } else {
                $room->setType(Room::TYPE_NONAC);
            }

            $this->em->persist($room);

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
