<?php

use BookShop\Context\AddToCartContext;

use BookShop\Entity\User;
use BookShop\Entity\Book;

describe("BookShop\Context\AddToCartContext", function() {

    beforeEach(function() {
        $this->user = new User();
        $this->book = new Book();
        $this->context = new AddToCartContext($this->user, $this->book);
    });

    it("applies the Customer Role", function() {
        assertThat($this->context->getCustomer(), anInstanceOf("BookShop\Role\Customer"));
    });
    
    it("adds the book to the customers cart", function() {
        $this->context->execute();
        assertThat($this->context->getCustomer()->getCart()->contains($this->book), true);
    });
});
