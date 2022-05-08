<?php

namespace Auth;

class IAMToken implements Token
{
    private $private_key_path;

    private $serviceAccountId;

    private $serviceKid;

    public function __construct(string $privateKeyPath, string $serviceAccountId, string $serviceKid)
    {
        $this->private_key_path = $privateKeyPath;
        $this->serviceAccountId = $serviceAccountId;
        $this->serviceKid = $serviceKid;
    }

    public function getToken(): string
    {

    }

    private function makeJWT(): string
    {
        
    }
}