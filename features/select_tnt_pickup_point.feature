@selecting_pickup
Feature: Checking postcode and zipcode match
    In order to export shipping data to external shipping provider service
    As a Customer
    I want to be able to export shipments to external API

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships to "France"
        And the store has a zone "France" with code "FR"
        And this zone has the "France" country member
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And the store has "TNT" shipping method with "$10.00" fee within the "FR" zone
        And shipping method "TNT" has the selected "tnt" pickup point provider
        And the store has a payment method "Offline" with a code 'offline'
        And I am a logged in customer
        
        And I added product "PHP T-Shirt" to the cart

    @ui @javascript @bug
    Scenario: If I choose city, it is saved in the billing address
        Given I am at the checkout addressing step
        And I specify billing first name as "Jon"
        And I specify billing last name as "Snow"
        And I specify billing street as "Ankh Morpork"
        And I specify billing phone as "123456789"
        And I specify billing country as "France"
        And I specify billing postcode as "64200"
        And I select billing city as "Biarritz"
        And I try to complete the addressing step
        Then I should be on the checkout shipping step
        And I select "TNT" pickup point shipping method
        Then I choose the first option
        And I try to complete the shipping step
        Then I should be on the checkout payment step
        And I completed the payment step with "Offline" payment method
        Then I should be on the checkout summary step
        And I confirm my order
        Then I should see the thank you page