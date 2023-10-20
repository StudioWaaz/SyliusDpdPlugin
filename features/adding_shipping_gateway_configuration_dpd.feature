@managing_shipping_gateway_dpd
Feature: Creating shipping gateway
    In order to export shipping data to external shipping provider service
    As an Administrator
    I want to be able to add new shipping gateway with shipping method

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And the store has "DPD" shipping method with "$10.00" fee

    @ui
    Scenario: Creating DPD shipping gateway
        When I visit the create shipping gateway configuration page for "dpd-classic"
        And I select the "DPD" shipping method
        And I fill the "DPD username" field with "username"
        And I fill the "DPD password" field with "password"
        And I fill the "DPD customer number" field with "123456"
        And I fill the "DPD center number" field with "123"
        And I fill the "DPD country code" field with "250"
        And I fill the "Sender name" field with "Studio Waaz"
        And I fill the "Sender address" field with "Allée d'Aguiléra"
        And I fill the "Sender city" field with "Biarritz"
        And I fill the "Sender country" field with "FR"
        And I fill the "Sender postal code" field with "64200"
        And I fill the "Sender phone number" field with "0900000000"
        And I fill the "Sender email" field with "noreply@studiowaaz.com"
        And I fill the "Printer format" field with "PDF"
        And I add it
        Then I should be notified that the shipping gateway has been created
    

    @ui
    Scenario: Creating DPD shipping predict gateway
        When I visit the create shipping gateway configuration page for "dpd-predict"
        And I select the "DPD" shipping method
        And I fill the "DPD username" field with "username"
        And I fill the "DPD password" field with "password"
        And I fill the "DPD customer number" field with "123456"
        And I fill the "DPD center number" field with "123"
        And I fill the "DPD country code" field with "250"
        And I fill the "Sender name" field with "Studio Waaz"
        And I fill the "Sender address" field with "Allée d'Aguiléra"
        And I fill the "Sender city" field with "Biarritz"
        And I fill the "Sender country" field with "FR"
        And I fill the "Sender postal code" field with "64200"
        And I fill the "Sender phone number" field with "0900000000"
        And I fill the "Sender email" field with "noreply@studiowaaz.com"
        And I fill the "Printer format" field with "PDF"
        And I add it
        Then I should be notified that the shipping gateway has been created

    @ui
    Scenario: Creating DPD shipping relay gateway
        When I visit the create shipping gateway configuration page for "dpd-relay"
        And I select the "DPD" shipping method
        And I fill the "DPD username" field with "username"
        And I fill the "DPD password" field with "password"
        And I fill the "DPD customer number" field with "123456"
        And I fill the "DPD center number" field with "123"
        And I fill the "DPD country code" field with "250"
        And I fill the "Sender name" field with "Studio Waaz"
        And I fill the "Sender address" field with "Allée d'Aguiléra"
        And I fill the "Sender city" field with "Biarritz"
        And I fill the "Sender country" field with "FR"
        And I fill the "Sender postal code" field with "64200"
        And I fill the "Sender phone number" field with "0900000000"
        And I fill the "Sender email" field with "noreply@studiowaaz.com"
        And I fill the "Printer format" field with "PDF"
        And I add it
        Then I should be notified that the shipping gateway has been created