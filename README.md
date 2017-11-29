# Module.MinifiedResourceDeployment

A resource deployment provider for Rhubarb that minifies Javascript during deployment.

Minification only occurs for local JS resources and if the original source file is
newer than the previously minified verison. 

## Installation

Simply require the module:

```
composer require rhubarbphp/rhubarbphp/module-minified-resource-deployment
```

## Usage

Simply switch to using the `MinifiedResourceDeploymentProvider` as your chosen provider in your
`inialise()` method of your Rhubarb `Application` object. 

```php
ResourceDeploymentProvider::setProviderClassName(MinifiedResourceDeploymentProvider::class);
```
