<?php

namespace App\Contracts;

/* Why we implemented this interface? 
you can write code that works on either type (Graduate/ Event)
without caring which one it actually is */


interface PublishableInterface
{
    public function isPublished(): bool;
}
