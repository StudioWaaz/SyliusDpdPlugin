![Logo](github.png)

![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/StudioWaaz/SyliusDpdPlugin/build.yml?style=for-the-badge)
# WaazSyliusDpdPlugin

This plugin allows you to generate shipping labels for DPD carrier.



## Features

- Shipping label export (for classic, predict & relay shipping)


## Installation

**Prerequisite**: you must first configure/install the `bitbag/shipping-export-plugin` &  `setono/sylius-pickup-point-plugin`

Install plugin with composer

```bash
composer require waaz/sylius-dpd-plugin
```
Add plugin dependencies to your `config/bundles.php` file:

```php
return [
    ...

    Waaz\SyliusDpdPlugin\WaazSyliusDpdPlugin::class => ['all' => true],
];
```

## Configuration
`DPD_API_PICKUP_POINTS_KEY` should be set if you wish to use pickup point deliveries.

You can configure this plugin by creating a file `config/packages/waaz_sylius_dpd_plugin`:
```yml
# Defaults values
waaz_sylius_dpd:
    sandbox: true  # Sandbox mode
    weight_unit: 'g' # 'g' or 'kg'. Weight unit you use in your shop

```
<!--
## Installation (*pickup point part*)

**Prerequisite**: you must first configure/install the `setono/sylius-pickup-point-plugin`

-->
## Running Tests

- PHPSpec

```bash
vendor/bin/phpspec run
```

- Behat (non-JS scenarios)

```bash
vendor/bin/behat --strict --tags="~@javascript"
```

- Behat (JS scenarios)

    1. [Install Symfony CLI command](https://symfony.com/download).

    2. Start Headless Chrome:

    ```bash
    google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
    ```

    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:

    ```bash
    symfony server:ca:install
    APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
    ```

    4. Run Behat:

    ```bash
    vendor/bin/behat --strict --tags="@javascript"
    ```

- Psalm

    ```bash
    vendor/bin/psalm
    ```
    
- PHPStan

```bash
vendor/bin/phpstan analyse -c phpstan.neon -l max src/  
```

- Coding Standard
  
```bash
vendor/bin/ecs check src
```

## Author

- [@ehibes](https://www.github.com/ehibes) for [Studio Waaz](https://www.studiowaaz.com)
## License

This plugin's source code is completely free and released under the terms of the MIT license.

