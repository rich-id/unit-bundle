# Available test cases

In the following TestCase, it is recommanded to avoid overriding `setUp()` and `tearDown()` and to use respectively `beforeTest()` and `afterTest()` instead. This makes sure that no initialization nor finalization action is missing. Moreover, it can insert an action managed by the test case to respect the workflow. For instance, loading the container before the `beforeTest()`.

Moreover, each test case uses from the MockeryPHPUnitIntegration so no need to use the integration trait.


### MockeryTestCase

The MockeryTestCase is almost the same as the initial except the `setUp()` and `tearDown()` management which is replaced in the test by `beforeTest()` and `afterTest()`.


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


### ControllerTestCase

The ControllerTestCase provides a set of useful functions. Note that for this test, you will probably need to load a container using the `@WithContainer` or to use fixtures with `@WithFixtures`.

- `getCsrfToken()`: Generates a CSRF token based on the Class given or the CSRF token id for the input client.

- `parseQueryParams()`: Encodes the input array into a list of query params, beginning by `?`.

- `getJsonContent()`: Returns an array or object corresponding to the JSON response linked to the client.


### RepositoryTestCase

In the following list, it is the only test case that relies on fixtures. The entity linked to repository must be set using the `public const ENTITY`. Moreover, the `beforeTest()` function will be executed after the initialization of the repository.

```php
use RichCongress\Bundle\UnitBundle\TestCase\RepositoryTestCase;

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



