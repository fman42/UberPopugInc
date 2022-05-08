<?php

namespace Root\Yqm\Auth;

use Root\Yqm\Client\YMQClient;
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

class IAMToken implements IToken
{
    private $private_key_path;

    private $serviceAccountId;

    private $serviceKid;

    private $client;

    public function __construct(string $privateKeyPath, string $serviceAccountId, string $serviceKid, YMQClient $client)
    {
        $this->private_key_path = $privateKeyPath;
        $this->serviceAccountId = $serviceAccountId;
        $this->serviceKid = $serviceKid;
        $this->client = $client;
    }

    public function getToken(): string
    {
        $request = $this->client->post('/iam/v1/tokens', [
            'json' => [
                'jwt' => $this->makeJWT()
            ]
        ]);
    
        $response = json_decode((string) $request->getBody());
        return $response->iamToken;
    }

    private function makeJWT(): string
    {
        $now = time();
        $claims = [
            'aud' => sprintf('%s/iam/v1/tokens', $this->client->getBaseUrl()),
            'iss' => $this->serviceAccountId,
            'iat' => $now,
            'exp' => $now + 360
        ];

        $header = [
            'alg' => 'PS256',
            'typ' => 'JWT',
            'kid' => $this->serviceKid
        ];        

        $key = JWKFactory::createFromKeyFile(
            file_get_contents($this->private_key_path),
            $claims,
            $header
        );

        $jws = JWSFactory::createJWSToCompactJSON(
            $claims,                   
            $key,                         
            $header
        );

        return $jws;
    }
}