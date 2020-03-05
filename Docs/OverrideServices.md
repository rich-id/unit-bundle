# Override services in test containers

## Overriding services with stub services

The `OverrideServiceInterface` allows to replace systematically the target service with an instance of this interface. This is especially useful to isolate your application from every outside calls such as API, logging, etc.

You need to declare the classes as services. If you do not use the autoconfiguration, please tag the services as `rich_congress.unit_bundle.override_service`;

You can use the `OverrideServiceInterface` to do it.

```php
use RichCongress\Bundle\UnitBundle\OverrideService\OverrideServiceInterface;
use Symfony\Component\HttpKernel\Log\Logger;

class LoggerStub extends Logger implements OverrideServiceInterface
{
    public static function getOverridenServiceNames(): ?string
    {
        return 'logger';
    }

    public function log()
    {
        // ...
    }   
}
```

Or you can use the `AbstractOverrideService` if you are not restricted by any extend.

```php
use RichCongress\Bundle\UnitBundle\OverrideService\AbstractOverrideService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStub extends AbstractOverrideService implements SessionInterface
{
    public const OVERRIDEN_SERVICES = 'session';

    public function set()
    {
        // ...
    }
}
```


## Use dynamic mocks (legacy)

In some cases, you may prefer to mock systematically the services dynamically, ie from a class. To do so, create a class that implements the `MockedServiceOnSetUpInterface` and make sure the `rich_congres_unit.mocked_services` points to its class name.

```php
use RichCongress\Bundle\UnitBundle\Mock\MockedServiceOnSetUpInterface;

class MockService implements MockedServiceOnSetUpInterface
{
    /**
     * @return array
     */
    public static function getMockedServices(): array
    {
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->andReturn(new Response());

        $mocks['guzzle.client.dummy_api'] = $client;
        
        return $mocks;
    }
}
```


## Available default service stubs

The bundles provides some services that can override Symfony services for easier testing. To use it, simply enable it from the [configuration](Configuration.md). For instance, to stub the `logger` service, set the `rich_congress_unit.stubs.logger` entry to `true`.

The following list is the various services that can be stubbed from the [configuration](Configuration.md):
- `logger`