@managing_postcode_zipcode
Feature: Checking postcode and zipcode match
    In order to export shipping data to external shipping provider service
    As an Administrator
    I want to be able to export shipments to external API

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships to "France"
        And the store has a zone "World" with code "WR"
        And this zone has the "France" country member
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And the store ships everywhere for free
        And I am a logged in customer

    @ui @javascript
    Scenario: City field is converted to select and show cities
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify billing country as "France"
        And I specify billing postcode as "64200"
        Then I should have "Biarritz" city available to choose from

    @ui @javascript
    Scenario: If I choose city, it is saved in the billing address
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify billing first name as "Jon"
        And I specify billing last name as "Snow"
        And I specify billing street as "Ankh Morpork"
        And I specify billing phone as "123456789"
        And I specify billing country as "France"
        And I specify billing postcode as "64200"
        And I select billing city as "Biarritz"
        And I try to complete the addressing step
        Then I should be on the checkout shipping step
    
    @ui @javascript
    Scenario: If I choose a city that not match with the postcode, I should be notified
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify input billing city as "Paris"
        And I specify billing first name as "Jon"
        And I specify billing last name as "Snow"
        And I specify billing street as "Ankh Morpork"
        And I specify billing phone as "123456789"
        And I specify billing country as "France"
        And I specify billing postcode as "64200"
        And I try to complete the addressing step
        Then I should be notified that the city does not match the postcode