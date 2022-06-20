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

### Populate the shop finder database with demo data

Use the API (making requests via Insomnia, Postman, or curl on the command line):
* Request an authentication token: POST http://localhost:8000/api/oauth/token
  * `client_id` (found in the [admin backend](http://localhost:8000/admin#/sw/integration/index) as "Access key ID" after enabling a user by adding a permission to use the REST API instead, like documented here:
    https://developers.shopware.com/developers-guide/rest-api/
> To enable access to the REST API, the shop owner must authorize one (or more) users in the Shopware backend.

> Simply open the Shopware backend and open the User Administration window, under Settings. From the list of existing users displayed on this window, select Edit for the desired user and mark the enabled checkbox in the API Access section.

> You will get a randomly generated API access key, which needs to be included in your API requests for authentication. After clicking Save, the changes will take effect. If the edited user is currently logged in, you might need to clear the backend cache, and then log out and log in for your changes to take effect.
  * `client_secret` (called "Secret or secure access key" in the backend)
  * `grant_type: client_credentials`

The request body might look like this as JSON in the insomnia client:
```json
{
    "client_id": "SWUAOFPSUZDGWMLWRXR6ETZ5AG",
    "client_secret": "RFhWSldVQTZ4emc1d2dZRHhla3IyRmMxb29PdmFaR0xROWJIQ3U",
    "grant_type": "client_credentials"
}
```

* Copy the `access_token` from the response
* Call the faker factory generator action: POST http://localhost:8000/api/v1/_action/ingos_ingorance/generate

Verify that the table `ingos_ingorance` has been populated (using Adminer, PhpMyAdmin, or the SQL CLI), e.g.
http://localhost:8001/?server=mysql%3A3306%2Fshopware&username=app&db=shopware&select=ingos_ingorance

### Use version-agnostic, language-agnostic, relative paths

Keep in mind that, in our code, we must always use version-agnostic calls, e.g.
`@Route("/api/v{version}/_action/ingos_ingorance/generate")`
so that the Shopware core can use the appropriate / latest version.

When a plugin is reviewed for release in the official Shopware extension / app store,
reviewers will make sure that there is no hard-coded dependency
* of a specific API version (plugin might be tested with an arbitrary version like `v8`)
* of an absolute path (plugin might be tested in a subdirectory of another, existing Shopware installation)
* of a specific language (plugin might be tested with a non-default shop admin language like Dutch)

## Files

IngoSFraktalistheme uses the recommended directory structure of a symfony bundle used to extend Shopware. `src/Core/Api` defines the demo data controller in `DemoDataController.php`, `src/Core/Content` defines our new custom `Ingorance` entity using 3 files (`IngoranceCollection.php`, `IngoranceDefinition.php`, and `IngoranceEntity.php`). The migration code to extend the SQL database is in `src/Migration`.

## Known Issues

* [ ] system config service not working properly (see. 2022_1)
* [x] logger not working properly (see 2021_3)
* [x] incorrect / inconsistent capitalization of folder names?
  (inconsistent and unintuitive, but consistent with the Shopware tutorial)
* [x] localize every hard-coded template string using snippets (lesson 10)
* [ ] localize images that contain text: best practice?
* [ ] loop fake shops: paginate large amount of data: best practice?
* [x] we should not need a full path to loop over our custom data in twig
* [ ] analyze, fix, refactor using tooling (PhpStan, SonarLint, built-in PhpStorm tools, ...)
* [ ] recap, read, and understand everything that I did (would-be code review preparation)
