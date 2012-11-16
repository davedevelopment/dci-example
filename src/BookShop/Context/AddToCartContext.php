<?php

namespace BookShop\Context;

use BookShop\Entity\User;
use BookShop\Entity\Book;
use BookShop\Role\Customer;

class AddToCartContext 
{
    protected $customer;
    protected $book;

    public function __construct(User $user, Book $book)
    {
        $this->customer = new Customer($user);
        $this->book = $book;
    }

    public function execute()
    {
        $this->customer->addToCart($this->book);
    }

    public function getCustomer()
    {
        return $this->customer;
    }
}
