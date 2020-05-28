## Unreleased

#### New features

- Add an assertSubSet function to assert that the values of an array is a subset of the values of another (ex: [3, 2, 5] is a subset of [1, 2, 42 => 3, 4, 'some key' => 5]).

## Version 1.0.2

#### Bug fix

- Fix bad annotation checking for the container.
- Fix some documentation issue again.
- Handles errors for the AbstractCommand.
- Fix CommandTestCase bugs related to the usage of Helpers withing the command.
- Fix not mocked services during the fixture loading.
- Fix Fixtures creation when the property is inherited.


#### New features

- Add handy functions for the fixture creation.
- Add the `DATE_FORMAT` function to the SQLite language that behave like MySQL function.
- Add better `ConnectionFactory`.
- Add the `buildObject` function for all tests.



## Version 1.0.1

#### Bug fix

- Fix composer annotation autoloading.
- Fix inspector errors and code cleaning.
- Fix some documentation issue.
