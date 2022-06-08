<?php

namespace App\Security;

use App\Session\SessionService;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Secure;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginAuthenticator extends AbstractGuardAuthenticator
{

    protected $encoder;
    protected $sessionService;

    public function __construct(UserPasswordEncoderInterface $encoder, SessionService $sessionService)
    {
        $this->encoder = $encoder;
        $this->sessionService = $sessionService;
    }
    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'security_login'
            && $request->isMethod('POST');
    }
    public function getCredentials(Request $request)
    {
        // array with 3 informations Email, password, token
        //     #parameters: array:1 [▼
        //   "login" => array:3 [▼
        //     "Email" => "user0@gmail.com"
        //     "password" => "user"
        //     "_token" => "3e0f9c0b953e76ad71f2f.lKbGd2hYPw9jDE1A_4AkHlnkK2ZgxjF_Bq7M7gVhSkw.4uqjACUtBj0ERzQZzuccWy2oRC0w8ndMSd6Enzw5Mzbm7aQyWDp2XAJHeQ"
        //         ]
        //     ] 
        return $request->request->get('login');
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            return $userProvider->loadUserByUsername($credentials['email']);
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException("Cette adresse email n'est pas connue");
        }
        // here the userprovider is an object which is capable of searching with the help of security.yaml providers in the entity user by its email 
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //here the password is checked 

        $isValid =  $this->encoder->isPasswordValid($user, $credentials['password']);
        if (!$isValid) {
            throw new AuthenticationException(" Les informations de connexion ne correspondent pas");
        }
        return true;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {

        if ($this->sessionService->getSessionDetails() === array(0)) {
            return new RedirectResponse('http://localhost/final%20project/hotel/public/');
        } else {
            $resa = $this->sessionService->getSessionDetails();
            //dd($this->sessionService->getSessionDetails());
            if (isset($resa[0])) {
                $resaID = $resa[0]->getId();

                $url = "http://localhost/final%20project/hotel/public/payment/" . $resaID . "/0";

                return new RedirectResponse($url);
            } else {
                return new RedirectResponse('http://localhost/final%20project/hotel/public/');
            }
        }
    }
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
    }
    public function supportsRememberMe()
    {
    }
}
