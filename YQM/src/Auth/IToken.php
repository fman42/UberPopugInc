<?php

namespace Root\Yqm\Auth;

interface IToken
{
    public function getToken(): string;
}