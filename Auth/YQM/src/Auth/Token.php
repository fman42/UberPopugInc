<?php

namespace Auth;

interface Token
{
    public function getToken(): string;
}