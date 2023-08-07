@managing_shipping_export_tnt
Feature: Managing shipping gateway
    In order to export shipping data to external shipping provider service
    As an Administrator
    I want to be able to export shipments to external API

    Background:
        Given the store operates on a single channel in the "United States" named "Web-US"
        And I am logged in as an administrator
        And the store has "TNT" shipping method with "$10.00" fee
        And there is a registered "tnt" shipping gateway for this shipping method named "tnt"
        And it has "account_number" field set to "123"
        And it has "sender_name" field set to "Waaz"
        And it has "sender_address1" field set to "Allée d'Aguiléra"
        And it has "sender_address2" field set to "Etage 2"
        And it has "sender_city" field set to "BIARRTITZ"
        And it has "sender_zip_code" field set to "64200"
        And it has "sender_phone_number" field set to "0900000000"
        And it has "sender_contact_first_name" field set to "Ibes"
        And it has "sender_contact_last_name" field set to "Mongabure"
        And it has "sender_email_address" field set to "noreply@studiowaaz.com"
        And it has "sender_fax_number" field set to "0900000001"
        And it has "sender_type" field set to "ENTERPRISE"
        And it has "receiver_type" field set to "INDIVIDUAL"
        And it has "label_format" field set to "STDA4"
        And the store has a product "Chicken" priced at "$2" in "Web-US" channel
        And customer "user@bitbag.pl" has placed 5 orders on the "Web-US" channel in each buying 5 "Chicken" products
        And the customer set the shipping address "Mike Ross" addressed it to "350 5th Ave", "10118" "New York" in the "United States" to orders
        And those orders were placed with "TNT" shipping method
        And set product weight to "10"
        And set units to the shipment

    @ui
    Scenario: Seeing shipments to export
        When I go to the shipping export page
        Then I should see 5 shipments with "New" state
