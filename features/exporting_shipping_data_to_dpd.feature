@managing_shipping_export_dpd
Feature: Managing shipping gateway
    In order to export shipping data to external shipping provider service
    As an Administrator
    I want to be able to export shipments to external API

    Background:
        Given the store operates on a single channel in the "United States" named "Web-US"
        And I am logged in as an administrator
        And the store has "DPD" shipping method with "$10.00" fee
        And there is a registered "dpd-predict" shipping gateway for this shipping method named "dpd"
        And it has "username" field set to "username"
        And it has "password" field set to "password"
        And it has "customer_number" field set to "123"
        And it has "customer_centernumber" field set to "123456"
        And it has "customer_countrycode" field set to "250"
        And it has "sender_name" field set to "Studio Waaz"
        And it has "sender_street" field set to "Allée d'Aguiléra"
        And it has "sender_city" field set to "Biarritz"
        And it has "sender_postalcode" field set to "64200"
        And it has "sender_country" field set to "FR"
        And it has "sender_phone" field set to "0900000000"
        And it has "sender_email" field set to "noreply@studiowaaz.com"
        And it has "sender_commercial_address" field set to "true"
        And it has "printer_format" field set to "PDF"
        And the store has a product "Chicken" priced at "$2" in "Web-US" channel
        And customer "user@bitbag.pl" has placed 5 orders on the "Web-US" channel in each buying 5 "Chicken" products
        And the customer set the shipping address "Mike Ross" addressed it to "350 5th Ave", "10118" "New York" in the "United States" to orders
        And those orders were placed with "DPD" shipping method
        And set product weight to "10"
        And set units to the shipment

    @ui
    Scenario: Seeing shipments to export
        When I go to the shipping export page
        Then I should see 5 shipments with "New" state
