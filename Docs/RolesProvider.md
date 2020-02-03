# Role provider

You can use phpunit's ```@dataProvider``` to automaticaly run a test with a
list of different values. We can use this to test a route against different
roles with a single unit test. To acheive this we will need to

1. list the roles and how to log as a user of that role
2. create a dataProvider giving for each role the expectations we want
(usually a http code)
3. write the test using the `@dataProvider`

## 1. Listing the roles

Add in your `rich_congress_unit.yaml` [configuration](Configuration.md) file, you can add the following configuration:

```yaml
rich_congress_unit:
    # Dictionnary where the key is the name of the role (displayed when a
    # failure happens), and the value is the reference to an entity used
    # to do the login (the entity is given to LogicalTestCase::authenticate()).
    test_roles:
        NotLogged: ''
        User: 'user-1'
        Admin: 'user-2'
```

## 2. Create a dataProvider

To create a roles provider, you need to create a Trait to gather them in one place.

```php
trait RolesProvidersTrait
{
    /**
     * @return array
     */
    public function rolesMustBeLoggedProvider(): array
    {
        return $this->rolesProvider(
            // rolesProvider is an utility to map your expectations with the
            // configured roles. It takes an array with the roles as keys and
            // your expectations as values.
            array(
                'NotLogged' => Response::HTTP_FORBIDDEN,
                'User'      => Response::HTTP_OK,
                'Admin'     => Response::HTTP_OK,
            )
        );
    }
    
    /**
     * @return array
     */
    public function rolesWithDifferentExpectations(): array
    {
        return $this->rolesProvider(
            // You can also give different expectations, see 3. Create a unittest
            // testWithDifferentExpectations to see how it translates in the test
            // function signature.
            array(
                'NotLogged' => Response::HTTP_FORBIDDEN,
                'User'      => array(Response::HTTP_OK),
                'Admin'     => array(Response::HTTP_OK, 'other expectation'),
            )
        );
    }

    /**
     * @return array
     */
    public function rolesWithExtraRoles(): array
    {
        return $this->rolesProvider(
            array(
                'NotLogged' => Response::HTTP_FORBIDDEN,
                'User'      => Response::HTTP_OK,
                'Admin'     => Response::HTTP_OK,
            ),
            // You can also provide extra roles, thoses are added to the list
            // of default roles. Like with regular roles you provide the role
            // name as key and then the expectations as value, but the first
            // expectation must be the user to use to log in as.
            array(
                'SpecialCase' => array('user-3', Response::HTTP_OK)
            )
        );
    }
}
```

To use a roles provider, import the trait within the Test class and use the PHPUnit `@dataProvider` annotation.

```php
class ExampleControllerTest extends ControllerTestCase
{
    use RolesProvidersTrait;
    
    /**
     * @dataProvider rolesMustBeLoggedProvider
     */
    public function testSomething(string $user, string $expectedStatusCode): void
    {
        $client = $this->createClientWith($user);
        $client->request('GET', 'url');
    
        self::assertStatusCode($expectedStatusCode, $client);
    }   
}
```
