Feature: User adds a book to their cart
    In order to purchase a book
    As a user
    I need to be able to add the book to my cart

    Scenario: User adds a book to their cart
        Given I am a logged in user
        And a book exists
        And I am looking at the book's page
        When I click the add book to cart button
        Then the book should be in my cart
