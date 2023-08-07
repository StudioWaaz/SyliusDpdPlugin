![Logo](github.png)

![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/StudioWaaz/SyliusTntPlugin/build.yml?style=for-the-badge)
![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/quality/g/StudioWaaz/SyliusTnTPlugin?style=for-the-badge)
# WaazSyliusTntPlugin

This plugin allows you to generate shipping labels for TNT carrier.



## Features

- Shipping label export
- Check that the postal code and city match for TNT : for this feature, if the country chosen is 'FR' then the city field becomes a select with city proposals from the tnt webservice


## Installation (*exporting label part*)

**Prerequisite**: you must first configure/install the `bitbag/shipping-export-plugin`

Install plugin with composer

```bash
composer require waaz/sylius-tnt-plugin
```
Add plugin dependencies to your `config/bundles.php` file:

```php
return [
    ...

    Waaz\SyliusTntPlugin\WaazSyliusTntPlugin::class => ['all' => true],
];
```

Add route in your `config/routes/sylius_shop.yaml` file:
```yaml
...
waaz_tnt_shop:
    resource: "@WaazSyliusTntPlugin/Resources/config/routing/shop_tnt.yaml"
```

Add parameter validation_groups in your `config/services.yaml` file:
```yaml
parameters:
    ...
    sylius.form.type.checkout_address.validation_groups: ['sylius', 'tnt_address']
```

Run assets install command : `bin/console assets:install`

Add plugin asset in `templates/bundles/SyliusShopBundle/_scripts.html.twig` file
```twig
{% include '@SyliusUi/_javascripts.html.twig' with {'path': 'assets/shop/js/app.js'} %}
{% include '@SyliusUi/_javascripts.html.twig' with {'path': 'bundles/waazsyliustntplugin/js/tnt-city.js'} %}
```

## Configuration
You can configure this plugin by creating a file `config/packages/waaz_sylius_tnt_plugin`:
```yml
# Defaults values
waaz_sylius_tnt:
    username: 'login' # Enter your tnt username here. You should use an environment variable like `%env(TNT_PASSWORD)%`
    password: 'password' # Same for password
    sandbox: true  # Sandbox mode
    weight_unit: 'g' # 'g' or 'kg'. Weight unit you use in your shop
    city_select_classes: 'ui dropdown' # Classes you want for city select field

```

## Installation (*pickup point part*)

**Prerequisite**: you must first configure/install the `setono/sylius-pickup-point-plugin`

    
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

