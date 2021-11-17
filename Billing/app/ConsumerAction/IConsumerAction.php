<?php

namespace App\ConsumerAction;

interface IConsumerAction
{
    public function handle() : bool;
}