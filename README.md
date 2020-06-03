Classifier error bundle
=======

## Automatic error generation from exceptions

The classifier represents application exception listeners.
In order for the exception to be converted into an HTTP response, specify the error code known to the classifier in the `code` field of the exception.

## Status

This package is currently in the active development.

## Requirements

* [PHP 7.2](http://php.net/releases/7_2_0.php) or greater
* [Symfony 4.4](https://symfony.com/roadmap/4.4)

## Installation
1. Require the bundle and a PSR 7/17 implementation with Composer:

    ```sh
    composer require itamit/classifier-error-bundle
    ```

1. Enable the bundle in `config/bundles.php` by adding it to the array:

    ```php
    Itamit\ClassifierErrorBundle\ClassifierErrorBundle::class => ['all' => true]
    ```

## Adding custom classifier error

1. Create a class that implements an interface `Cognitive\ErrorClassifierBundle\Provider\ErrorProviderInterface`

1. Register the created class as a service with a tag `cognitive_error_classifier.error_provider`
