<?php

namespace BookShop\Role;

use BookShop\Entity\Book;
use BookShop\Entity\User;
use BookShop\Util\Delegator;

class Customer
{
    use Delegator;

    public function __construct(User $user)
    {
        $this->delegateTo($user);
    }

    public function addToCart(Book $book)
    {
        $this->getCart()->add($book);
    }  
}
