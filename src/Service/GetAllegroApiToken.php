<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class GetAllegroApiToken
{

    private $userRepository;
    private $em;
    private $user;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, User $user)
    {

        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->user = $user;
    }

    /**
     * @param User $user
     * @return String
     */
    function getAccessToken(): string
    {
        $this->user = $this->userRepository->find($this->getUser());
        $authUrl = "https://allegro.pl.allegrosandbox.pl/auth/oauth/token?grant_type=client_credentials";
        $clientId = $this->user->getClientId();
        $clientSecret = $this->user->getClientSecret();

        $ch = curl_init($authUrl);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERNAME, $clientId);
        curl_setopt($ch, CURLOPT_PASSWORD, $clientSecret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $tokenResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($tokenResult === false || $resultCode !== 200) {
            exit ("Something went wrong");
        }

        $tokenObject = json_decode($tokenResult);
        $accessToken = $tokenObject->access_token;
        if (empty($this->user->getAccessToken()) || $$this->user->getAccessToken() != $accessToken) {
            $this->user->setAccessToken($accessToken);
            $this->em->persist($this->user);
            $this->em->flush();
        }
        return $tokenObject->access_token;
    }
}