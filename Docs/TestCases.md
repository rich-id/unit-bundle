# Available test cases

In the following TestCase, it is recommanded to avoid overriding `setUp()` and `tearDown()` and to use respectively `beforeTest()` and `afterTest()` instead. This makes sure that no initialization nor finalization action is missing. Moreover, it can insert an action managed by the test case to respect the workflow. For instance, loading the container before the `beforeTest()`.

Moreover, each test case uses from the `MockeryPHPUnitIntegration` and the `FixturesCreationTrait` so no need to use the integration trait. It means you can either use Mocks in the test or the `buildObject` function to create an object.

To use a container or fixtures, please follow this [documentation](Annotations.md).


### CommandTestCase

The CommandTestCase helps to focus on the command to test. Use the `beforeTest()` method to set the command and let the test case create the CommandTester for you. To execute the command, use the method `execute()`.

```php
use RichCongress\Bundle\UnitBundle\TestCase\CommandTestCase;

class CommandTest extends CommandTestCase
{
    public function beforeTest() : void
    {
        $this->command = new Command();
    }

    public function testExecution(): void
    {
        // ...
    
        $output = $this->execute([
            '--parameter' => 'value'
        ]);

        self::assertStringContainsString('Everything is alright', $output);
    }
}
```


### ConstraintTestCase

The ConstraintTestCase handles the context creation and the validation. Use the `beforeTest()` method to initialize the test. If the given Constraint is also a Validator, or the given Validator is also a Constraint, the object will be used both as a constraint and validator.

```php
use RichCongress\Bundle\UnitBundle\TestCase\ConstraintTestCase;

class ConstraintTest extends ConstraintTestCase
{
    public function beforeTest() : void
    {
        $this->constraint = new Constraint();
        $this->validator = new ConstraintValidator();
    }

    public function testValidate(): void
    {
        // ...
    
        $violations = $this->validate($object);

        self::assertEmpty($violations);
    }
}
```

Property constraints can access the object containing the value we are trying to validate from the context: `$this->constraint->getObject()`. To provide that object in your test you can pass it as the second argument of the `$this->validate()` function provided by the ConstraintTestCase:

```php
class ConstraintTest extends ConstraintTestCase
{
    public function testValidate(): void
    {
        $object = // ...

        $violations = $this->validate($object->property, $object);

        self::assertEmpty($violations);
    }
}
```

### ControllerTestCase

The ControllerTestCase provides a set of useful functions. Note that for this test, you will probably need to load a container using the `@WithContainer` or to use fixtures with `@WithFixtures`.

- `getCsrfToken()`: Generates a CSRF token based on the Class given or the CSRF token id for the input client.

- `parseQueryParams()`: Encodes the input array into a list of query params, beginning by `?`.

- `getJsonContent()`: Returns an array or object corresponding to the JSON response linked to the client.

```php
/**
 * @WithFixtures
 */
class ExampleControllerTest extends ControllerTestCase
{
    public function testRoute(): void
    {
        $client = self::createClient();
        $client->request('POST', '/post/route', [
            '_token' => $this->getCsrfToken('csrf_token_id'),
            // ...
        ]);
        
        self::assertStatusCode(Response::HTTP_CREATED, $client);
        self::assertArrayKeyExists('id', self::getJsonContent($client));
    }
}
```


### RepositoryTestCase

The entity linked to repository must be set using the `public const ENTITY`. Moreover, the `beforeTest()` function will be executed after the initialization of the repository. You need to at least use `@WithContainer` annotation, and we recommand to use `@WithFixtures` to test your functions with the fixtures.

```php
use RichCongress\Bundle\UnitBundle\TestCase\RepositoryTestCase;

/**
 * @WithFixtures
 */
class UserRepositoryTest extends RepositoryTestCase
{
    public const ENTITY = User::class;

    public function testRepositoryClass(): void
    {
        self::assertInstanceOf(EntityRepository::class, $this->repository);
        self::assertSame(static::ENTITY, $this->repository->getClassName());
    }
}
```


### VoterTestCase

The VoterTestCase provides utilities to generate token and then authenticate a user to test the voter. Use the `beforeTest()` method to set an instance of the voter, and then `vote()` or `getToken()` to test your voter.

```php
use RichCongress\Bundle\UnitBundle\TestCase\VoterTestCase;

class DummyEntityVoterTest extends VoterTestCase
{
    public function beforeTest() : void
    {
        $this->voter = new Voter();
    }

    public function testVoteAnonymously(): void
    {
        // ...
    
        $response = $this->vote($entity, DummyEntityVoter::HAS_ACCESS_TO_ENTITY);

        self::assertSame(Voter::ACCESS_DENIED, $response);
    }

    public function testVoteLoggedIn(): void
    {
        // ...
    
        $response = $this->vote($entity, DummyEntityVoter::HAS_ACCESS_TO_ENTITY, $user);

        self::assertSame(Voter::ACCESS_GRANTED, $response);
    }
}
```



