<?php

use BookShop\Entity\User;
use BookShop\Entity\Book;
use BookShop\Role\Customer;

describe("BookShop\Role\Customer", function() {

    beforeEach(function() {
        $user = new User();
        $this->customer = new Customer($user);
    });

    describe("#addToCart", function() {
        it("puts the book in the cart", function() {
            $book = new Book;
            $this->customer->addToCart($book);
            assertThat($this->customer->getCart()->contains($book), true);
        });
    });
});
