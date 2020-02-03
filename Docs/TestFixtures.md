## Creating fixtures

A fixture is a piece of code that will describes how to populate the database, in this case for the tests. Hopefully, this bundle provides a lot of tools to create quickly a fixture.

To create a fixture, you must create a fixture class (for instance `tests/Resources/DataFixture/LoadUserData.php`) and extend it with `RichCongress\Bundle\UnitBundle\DataFixture\AbstractFixture`. 

Finally, declare it as a service within your test kernel. If you have autoconfigure enabled, you're done with the configuration. If not, you need to tag the fixtures services with `rich_congress.unit_bundle.data_fixture`.

```php
use RichCongress\Bundle\UnitBundle\DataFixture\AbstractFixture;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;

class LoadUserData extends AbstractFixture
{
    public function loadFixtures() : void{
        self::createObject('user-1', User::class, [
            'username' => 'username_1',
            'password' => 'unicorn_1',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ]);
    
        self::createObject('user-2', User::class, [
            'username' => 'username_2',
            'password' => 'unicorn_2',
            'roles'    => ['ROLE_USER'],
        ]);
    }
}
```

You can also get an entity with a reference initialized from another fixture. Doctrine provided a handy interface that can manage to load fixtures in the approriated order.

```php
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use RichCongress\Bundle\UnitBundle\DataFixture\AbstractFixture;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadUserData;

class LoadDummyEntityData extends AbstractFixture implements DependentFixtureInterface
{
    // ...
    
    public function getDependencies(): array
    {
        return [
            LoadUserData::class,
        ];       
    }
}
```


#### Useful functions

In this case, populating an object works also for private and protected properties.

- `createObject()`: Creates an object, populates it with the input data, persists it and attaches the input reference on it.

- `buildObject()`: Creates an object and populates it with the input data.

- `setValues()`: Populates the input object with the input data.

- `setValue()`: Set the input value of the input property for the input object.