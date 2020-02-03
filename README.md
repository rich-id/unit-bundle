Getting Started With RichCongressUnitBundle
=======================================

This version of the bundle requires Symfony 4.4+ and PHP 7.3+.

[![Package version](https://img.shields.io/packagist/v/richcongress/unit-bundle)](https://packagist.org/packages/richcongress/unit-bundle)
[![Build Status](https://img.shields.io/travis/richcongress/unit-bundle.svg?branch=master)](https://travis-ci.org/richcongress/unit-bundle?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/richcongress/unit-bundle/badge.svg?branch=master)](https://coveralls.io/github/richcongress/unit-bundle?branch=master)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/richcongress/unit-bundle/issues)
[![License](https://img.shields.io/badge/license-MIT-red.svg)](LICENSE.md)

The unit-bundle provides is suite for application testing. It provides wrappers to isolate tests, various test cases to avoid code redundancy and a easy fixtures management.

This bundle is a fork of the [chaplean/unit-bundle](https://github.com/chaplean/unit-bundle).


# Quick start

The unit-bundle requires almost no configuration but provides useful tools to test your code. Here is an basic example:

```php
class MainControllerTest extends ControllerTestCase
{
    /**
     * @WithFixtures
     * 
     * @return void
     */
    public function testIndex(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');
    
        self::assertStatusCode(Response::HTTP_OK, $client);
    }

    /**
     * @WithFixtures
     * 
     * @return void
     */
    public function testUserEdition(): void
    {
        $client = $this->createClientWith('user-1');
        $client->request('PUT', '/rest/users/self', ['name' => 'Karl']);
    
        self::assertStatusCode(Response::HTTP_OK, $client);
    
        $content = self::getJsonContent($client);
        self::assertArrayKeyExists('name', $content);
        self::assertSame('Karl', $content['name']);
    }
}
```


# Table of content

1. [Installation](#1-installation)
2. [Getting started](#2-getting-started)
    - [Configuration](Docs/Configuration.md)
    - [Available test cases](Docs/TestCases.md)
        - [MockeryTestCase](Docs/TestCases.md#mockerytestcase)
        - [CommandTestCase](Docs/TestCases.md#commandtestcase)
        - [ConstraintTestCase](Docs/TestCases.md#constrainttestcase)
        - [ControllerTestCase](Docs/TestCases.md#controllertestcase)
        - [RepositoryTestCase](Docs/TestCases.md#repositorytestcase)
        - [VoterTestCase](Docs/TestCases.md#votertestcase)
    - [Using the annotations](Docs/Annotations.md)
    - [Creating fixtures](Docs/TestFixtures.md)
    - [Overriding services with stub services](Docs/OverrideServices.md#overriding-services-with-stub-services)
    - [Use dynamic mocks (legacy)](Docs/OverrideServices.md#use-dynamic-mocks-legacy)
    - [Available default service stubs](Docs/OverrideServices.md#available-default-service-stubs)
    - [Role provider](Docs/RoleProvider.md)
4. [Versioning](#3-versioning)
5. [Contributing](#4-contributing)
6. [Hacking](#5-hacking)
7. [License](#6-license)


# 1. Installation

This version of the bundle requires Symfony 4.4+ and PHP 7.3+.

### 1.1 Composer

```bash
composer require richcongress/unit-bundle
```

### 1.2 AppKernel.php

After the installation, make sure that those 4 bundles are declared correctly within the Kernel's bundles list.

```php
new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['test' => true],
new Liip\FunctionalTestBundle\LiipFunctionalTestBundle::class    => ['test' => true],
new Liip\TestFixturesBundle\LiipTestFixturesBundle::class        => ['test' => true],
new RichCongress\Bundle\UnitBundle\RichCongressUnitBundle::class => ['test' => true],
```

## 1.3 Declare the PHP Extension

First and foremost, declare the PHPUnitExtension in the `phpunit.xml.dist`:

```xml
...

<extensions>
    <extension class="RichCongress\Bundle\UnitBundle\PHPUnit\PHPUnitExtension" />
</extensions>

...
```

## 1.4 Mandatory configuration 

By default, the bundle configures everything on its own but if the config has been overriden somewhere, you can override it back to the default by importing the configuration:

```yaml
imports:
    - { resource: '@RichCongressUnitBundle/Resources/config/config.yml' }
```

Or configure manually doctrine with something like this:

```yaml
parameters:
    doctrine.dbal.connection_factory.class: Liip\TestFixturesBundle\Factory\ConnectionFactory

doctrine:
    dbal:
        driver: pdo_sqlite
        user: test
        dbname: test
        path: '%kernel.cache_dir%/__DBNAME__.db'
        url: null
        memory: false
```


# 2. Getting started

- [Configuration](Docs/Configuration.md)
- [Available test cases](Docs/TestCases.md)
    - [MockeryTestCase](Docs/TestCases.md#mockerytestcase)
    - [CommandTestCase](Docs/TestCases.md#commandtestcase)
    - [ConstraintTestCase](Docs/TestCases.md#constrainttestcase)
    - [ControllerTestCase](Docs/TestCases.md#controllertestcase)
    - [RepositoryTestCase](Docs/TestCases.md#repositorytestcase)
    - [VoterTestCase](Docs/TestCases.md#votertestcase)
- [Using the annotations](Docs/Annotations.md)
- [Creating fixtures](Docs/TestFixtures.md)
- [Overriding services with stub services](Docs/OverrideServices.md#overriding-services-with-stub-services)
- [Use dynamic mocks (legacy)](Docs/OverrideServices.md#use-dynamic-mocks-legacy)
- [Available default service stubs](Docs/OverrideServices.md#available-default-service-stubs)
- [Role provider](Docs/RoleProvider.md)


# 3. Versioning

unit-bundle follows [semantic versioning](https://semver.org/). In short the scheme is MAJOR.MINOR.PATCH where
1. MAJOR is bumped when there is a breaking change,
2. MINOR is bumped when a new feature is added in a backward-compatible way,
3. PATCH is bumped when a bug is fixed in a backward-compatible way.

Versions bellow 1.0.0 are considered experimental and breaking changes may occur at any time.


# 4. Contributing

Contributions are welcomed! There are many ways to contribute, and we appreciate all of them. Here are some of the major ones:

* [Bug Reports](https://github.com/richcongress/unit-bundle/issues): While we strive for quality software, bugs can happen and we can't fix issues we're not aware of. So please report even if you're not sure about it or just want to ask a question. If anything the issue might indicate that the documentation can still be improved!
* [Feature Request](https://github.com/richcongress/unit-bundle/issues): You have a use case not covered by the current api? Want to suggest a change or add something? We'd be glad to read about it and start a discussion to try to find the best possible solution.
* [Pull Request](https://github.com/richcongress/unit-bundle/merge_requests): Want to contribute code or documentation? We'd love that! If you need help to get started, GitHub as [documentation](https://help.github.com/articles/about-pull-requests/) on pull requests. We use the ["fork and pull model"](https://help.github.com/articles/about-collaborative-development-models/) were contributors push changes to their personnal fork and then create pull requests to the main repository. Please make your pull requests against the `master` branch.

As a reminder, all contributors are expected to follow our [Code of Conduct](CODE_OF_CONDUCT.md).


# 5. Hacking

You might use Docker and `docker-compose` to hack the project. Check out the following commands.

```bash
# Start the project
docker-compose up -d

# Install dependencies
docker-compose exec application composer install

# Run tests
docker-compose exec application bin/phpunit

# Run a bash within the container
docker-compose exec application bash
```


# 6. License

unit-bundle is distributed under the terms of the MIT license.

See [LICENSE](LICENSE.md) for details.