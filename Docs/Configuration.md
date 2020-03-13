# Configuration

The configuration can be edited from the `rich_congress_unit.yaml` file. Here is a sample of configuration:

```yaml
rich_congress_unit:
    db_cache:
        enable: true
        lifetime: 60
    mocked_services: Tests\App\Resources\MockedServices
    public_services:
        - 'monolog.logger'
    default_stubs:
        logger: true
    test_roles:
        NotLogged: ''
        Admin: 'user_1'
```

The following list gives more precisions about the configuration:

- `enable_db_caching`

The DB caching caches the database between 2 phpunit execution. This is especially significant for the application with a lot of fixtures which can take a while to initialize. With this option enabled, it does not increase the speed of the first compilation but the ones after.


- `mocked_services`

The mocked services configuration points to the mocked services class defined in the [documentation](OverrideServices.md#use-dynamic-mocks-legacy).


- `public_services`

By default, all services from the namespace `App\` are set public in the tests environment to easily override them. But some specific may be needed to set as public in this environment. This configuration allows to manually add those services.


- `default_stubs`

This is a list of default stubs provided by the unit-bundle. Find out the available services [here](OverrideServices.md#available-default-service-stubs).


- `test_roles`

This is a list of available roles for the DataProvider. Check out the [documentation](RolesProvider.md) to learn more about this feature.



## Enable Doctrine functions

The Unit bundle provides SQLite functions that behave like the MySQL one. The following configuration can be add to your doctrine configuration to enable the functions:

```yaml
doctrine:
    orm:
        entity_managers:
            app:
                dql:
                    datetime_functions:
                        DATE_FORMAT: RichCongress\Bundle\UnitBundle\Doctrine\Functions\Sqlite\DateFormatFunction
```