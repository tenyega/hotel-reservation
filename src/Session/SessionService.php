<?php

namespace App\Session;

use App\Entity\User;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{

    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    protected function getSession()
    {
        return $this->session->get('session', []);
    }


    protected function saveSession(array $session)
    {
        return $this->session->set('session', $session);
    }

    public function empty()
    {
        $this->saveSession([]);
    }

    public function add(array $data)
    {

        // 1. Find pannier in the session in the form of a table 

        // 2. if panier doesnt exist take blank table;

        // $cart = $request->getSession()->get('cart', []);
        $cart = $this->getSession();

        //key=>value and here its id=>value
        // 3.if exist  see the id already available in pannier  
        //4. if yes then add the data only 

        $cart[0] = $data;

        //6.save the table and update the session
        //$request->getSession()->set('cart', $cart);
        $this->saveSession($cart);


        //this method can be avoided with the help of argument resolver which is flashbaginterface directly in the method with a route 
        // $session->set('cart', $cart);
    }




    public function remove(string $data)
    {
        $cart = $this->getSession();
        unset($cart);
        $this->saveSession($cart);
    }

    /**
     * @return Session[]
     */
    public function getSessionDetails(): array
    {
        $detailedCart = 0;

        foreach ($this->getSession() as $fieldName => $value) {

            $detailedCart = $value;
        }
        if ($detailedCart) {

            return $detailedCart;
        } else {
            return array(0);
        }
    }
}
