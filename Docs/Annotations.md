# The Container and Fixtures annotations

Both `@WithContainer` and `@WithFixtures` can be used on the class documentation to load for each test inside respectfully a container or a container with the fixtures loaded.

However, if only some tests require these functionnality, you can add these annotation on the target tests only. It will loads what it should only for the target test.

These annotation works for all test cases brought by this bundle.


## Using @WithContainer

To use `@WithContainer`, add it to the class PHP Doc or the test PHP Doc. It will load an isolated container for the targetted tests. The following functions require this annotation:

- `createClient()`: Creates a KernelBrowser with a brand new container isolated from the test container. From this client, you can use the `request()` method to execute a request to your application. Note that you must initialize and execute a request only once per test.

- `getContainer()`: Returns the client if created, else returns the container of the test if exists, or initializes as last resort. Everytime the container is needed, this function is and must be used. The output of this function will be refered as *default container* for the rest of the documentation.

- `getManager()`: Returns the default EntityManager from the default container.

- `executeCommand()`: Execute a command using the default container and returns the display.

- `mockService()`: Replace the target service with the mentioned service for the specified container or the default container as a fallback. If the service is an object, it will just replace it. If the service is a string, it will create a `Mockery\Mock` of it. If no mock service is set, it will try to mock the overriden service.


## Using @WithFixtures

To use `@WithFixtures`, add it to the class PHP Doc or the test PHP Doc. It will load an isolated container with the fixtures loaded inside for the targetted tests. The previously described functions are available, and the following additionals functions are available with this annotation:

- `getReference()`: Gets the entity mapped to the reference.

- `authenticate()`: Authenticates the input user for the default container. If the input user is a string, it will consider it as a reference and will try to get it, else if the user is an instance of UserInterface, it will authenticate it.

- `createClientWith()`: Creates a client using `createClient()` and authenticates the input user using the `authenticate()` function

- `rolesProviders()`: Parses a `dataProvider` and replace the alias described in the `test_roles` [configuration](Configuration.md) by the entity references.


