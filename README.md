# IngoSFraktalistheme Demo Theme for Shopware 6

This theme is not intended for production use (yet), but rather the public bundle of a demo theme, and to practice for Shopware 6 developer certification. It is mostly based on the Shopware 6 developer training videos.

## Installation

Install and activate as a plugin or upload and choose as a theme.

### Verify the installation

* [x] plugin is compiles and checked without errors
* [x] plugin is installed
* [x] plugin is activated
* [x] database migrations have been executed, table ingos_ingorance exists
* [x] plugin configuration `showInStorefront` is activated
* [x] shopware cache is cleared
* [x] browser cache is cleared
* [x] storefront footer shows custom content
* [ ] ...

## Development

Check out this repository in a Shopware 6 development setup like [shopware/development](https://github.com/shopware/development) or [openmindculture/fractal-shopware-demo](https://github.com/openmindculture/fractal-shopware-demo)

into
`/development/custom/plugins`

and open the development repository as project root in your IDE (e.g. PhpStorm + Symfony and Shopware plugins)

## Functionality

IngoSFraktalistheme modifies default colours and adds custom data to the footer, currently provided by a faker factory which serves as a mock stub for a future API query.

DemoDateController returns country data which is used as a Criteria for a database lookup to find shops. This happens on FooterPageletLoaded in the backend while preparing data for the frontend.

## Files

IngoSFraktalistheme uses the recommended directory structure of a symfony bundle used to extend Shopware. `src/Core/Api` defines the demo data controller in `DemoDataController.php`, `src/Core/Content` defines our new custom `Ingorance` entity using 3 files (`IngoranceCollection.php`, `IngoranceDefinition.php`, and `IngoranceEntity.php`). The migration code to extend the SQL database is in `src/Migration`.
