# Exceptions

The Unit bundle can throw exception during the test, and most of them are related to a bad test writing. This documentation explains why the exception are thrown and how to fix it.


## BadTestRolesException

This exception is thrown whether one of the roles mentioned in a `@dataProviders` function does not exist or is missing. You can check the [documentation](Configuration.md) and properly set the `test_roles` entry properly. Each roles *must* be present in the data provider.


## ContainerNotEnabledException

This exception is thrown when the test tries to get the container or something that is supposed to be in the container. Everytime a service is get, you must mention the `@WithContainer` from the PHP doc of the test method or of the class. Check the [documentation](Annotations.md#using-withcontainer).


## CsrfTokenManagerMissingException

The CSRF Token manager is missing from the container. It means that it may not be installed at all (`composer require symfony/security-csrf`) or not enabled (in your configuration, check the configuration `framework.csrf_protection: true`). Check the [Symfony's documentation](https://symfony.com/doc/4.4/security/csrf.html) for more information.


## DuplicatedContainersException

This is thrown when two different containers are used. This may be caused by a service get before the client creation, or the use or 2 clients within the same test.

To fix it, make sure the client is created at the very beginning of the test (including the `beforeTest` method). And also do not create 2 clients in the same test. Remember that one test should only test one action.

## EntityManagerNotFoundException

The entity manager cannot be found. This means doctrine is not installed or badly configured. Please check the [Symfony's documentation](https://symfony.com/doc/4.4/doctrine.html).


## FixturesNotEnabledException

This exception is thrown when the test tries to get an object from a reference. As you tries to get a fixture, you must tell to the bundle to load them in the first place. Check the [documentation](Annotations.md#using-withfixtures).


## MethodNotFoundException

Somewhere in the test, it tries to get the configuration of a method that does not exist. If you think it is a bug from the bundle, please open an [issue](https://github.com/richcongress/unit-bundle/issues).