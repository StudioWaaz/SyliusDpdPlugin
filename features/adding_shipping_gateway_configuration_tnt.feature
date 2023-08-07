@managing_shipping_gateway_tnt
Feature: Creating shipping gateway
    In order to export shipping data to external shipping provider service
    As an Administrator
    I want to be able to add new shipping gateway with shipping method

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And the store has "TNT" shipping method with "$10.00" fee

    @ui
    Scenario: Creating TNT shipping gateway
        When I visit the create shipping gateway configuration page for "tnt"
        And I select the "TNT" shipping method
        And I fill the "Account number" field with "123"
        And I fill the "Sender name" field with "Waaz"
        And I fill the "Sender address" field with "Allée d'Aguiléra"
        And I fill the "Sender address 2" field with "Etage 2"
        And I fill the "Sender city" field with "Biarritz"
        And I fill the "Sender post code" field with "64200"
        And I fill the "Sender phone number" field with "0900000000"
        And I fill the "Sender contact firstname" field with "Ibes"
        And I fill the "Sender contact lastname" field with "Mongabure"
        And I fill the "Sender email" field with "noreply@studiowaaz.com"
        And I fill the "Sender fax number" field with "0900000001"
        And I fill the "Sender type" field with "ENTERPRISE"
        And I fill the "Receiver type" field with "INDIVIDUAL"
        And I fill the "Label format" field with "STDA4"
        And I add it
        Then I should be notified that the shipping gateway has been created