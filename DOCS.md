# Limerence

## Testing

Limerence is very to use, and everything you type will feel natural. There are mainly four functions that are used in a test: `test()`, `describe()`, `it()` and `expect()`, and they are all used in conjunction to each other.

#### `test()`
The `test()` function defines the boundaries of a test suite.

#### `describe()`
The `describe()` function defines the boundaries of a test case.

#### `it()`
The `it()` function describes the expectation of the test.

#### `expect()`
The `expect()` function makes the assertion.

### Example

**Dog.php**
```php
namespace App\Animals;

class Dog
{
    public function makeSound()
    {
        return "Woof woof!";
    }
}
```

**dogTest.php**
```php
require 'vendor/autoload.php';

use App\Animals\Dog;

test('Dog model test', function () {
    describe('function makeSound()', function () {
        it('should make a barking sound', function () {
            $dog = new Dog();

            expect($dog)->to->have->method('makeSound');

            $bark = $dog->makeSound();

            expect($bark)->to->be->a('string');
            expect($bark)->to->equal('Woof woof!');
        });
    });
});

```

## Assertions

A wide variety of assertions is available with Limerence.

#### equal(value)

```php
expect($user->name)->to->equal('James');
```

#### be(value)

```php
expect($user->name)->to->be->a('string');
```

Be can assert the following types: **string**, **number**, **int**, **integer**, **long**, **bool**, **boolean**, **float**, **double**, **real**, **object**, **array**, **callable**, **null**, **empty**, **file**, **writable**.

There's also an alias for `a` to make it look good when the expectation starts with a vowel:

```php
expect($user->age)->to->be->an('integer');
```

You can also call `be($value)` directly if a/an doesn't make sense.

```php
expect($user->age)->to->be('null');
```

*(Note that both `expect($user->age)->to->be->a('integer')` and `expect($user->age)->to->be('integer')` would still work.)*

#### have(value)

The have method can be used to assert properties and methods of objects.

```php
expect($user)->to->have->property('username');
```

```php
expect($user)->to->have->method('login');
```

#### contain(value|values)

Contain can be used to check if an array contain certain values or keys.

```php
expect($user->hobbies)->to->contain('Fishing');
```

which is the same as

```php
expect($user->hobbies)->to->contain->value('Fishing');
```

You can also check keys by calling

```php
expect($user->skills)->to->contain->key('Programming');
```

Contain can be used to check multiple keys/values as well by passing it an array as argument:

```php
expect($user->hobbies)->to->contain(['Fishing', 'Knitting', 'Sports']);
```

## Negating

All assertions can be negated by adding `not` to the assertion chain, and will behave as expected. For example:
```php
expect($user->name)->to->not->be->a('number');
```
or

```php
expect($user->name)->to->not->equal('John Doe');
```

## HTTP Requests

Limerence also includes functionality to test HTTP requests, which can be particularly useful when testing REST APIs. The protocol defaults to **http**, hostname defaults to **localhost**, port defaults to **80**, request method defaults to **GET**. Example:

#### request(method[, endpoint])

```php
test('/users', function () {
    describe('GET', function () {
        it('should return a list of users', function () {
            request('GET', '/users')
                ->expect(200)
                ->end(function ($err, $res) {
                    if ($err) return;

                    expect($res->body)->to->have->property('success');
                    expect($res->body->success)->to->be->a('boolean');
                    expect($res->body->success)->to->equal(true);

                    expect($res->body)->to->have->property('users');
                    expect($res->body->users)->to->be->an('array');
                });
        });
    });
});
```

#### expect(status)

The expect function makes an assertion of the response status code.

```php
get('/admin')
    ->expect(403) // Expects the server to respond with 403 Forbidden
    ->end(function ($err, $res) {
        // ...
    });
```

#### protocol(protocol)

Sets the protocol of the request. Can be either **http** or **https**. Defaults to **http**.

```php
get('/users')
    ->protocol('https') // Sets the protocol to https
    ->expect(200)
    ->end(function ($err, $res) {
        // ...
    });
```

#### at(hostname)

Sets the target hostname of the request. Defaults to **localhost**.

```php
get('/users')
    ->at('myapp.dev') // Sets the hostname to myapp.dev
    ->expect(200)
    ->end(function ($err, $res) {
        // ...
    });
```

#### on(port)

Sets the target port of the request. Defaults to **80**.

```php
get('/users')
    ->on(3000) // Sets the port to 3000
    ->expect(200)
    ->end(function ($err, $res) {
        // ...
    });
```

#### Short hand helpers

There's also short hand functions for GET, POST, PUT, PATCH and DELETE requests, for example:

```php
get('/users')
    ->expect(200)
    ->end(function ($err, $res) {
        // ...
    });
```

#### send(data[, json])

To send data, use the send function. It accepts an array of key/value pairs with the data, and optionally a flag that tells Limerence to send the data as JSON. If you set the json to true, it will automatically include a `Content-type: application/json` header for you. Example:

```php
post('/users')
    ->expect(201)
    ->send([
        'username'  => 'johnny',
        'email'     => 'john.smith@example.com',
        'password'  => 'password123',
    ]) // no json flag, data will be sent as normal form data
    ->end(function ($err, $res) {
        // ...
    });
```

```php
put('/users/15')
    ->expect(200)
    ->send([
        'firstname' => 'James',
        'lastname'  => 'Smith',
    ], true) // Data will be sent as JSON
    ->end(function ($err, $res) {
        // ...
    });
```

#### with(header, value)

To attach a header to the request, simply use the `with` function. It takes a key and a value, for example:

```php
delete('/users/15')
    ->expect(200)
    ->with('Authorization', 'Bearer eyJhbGciOiJIUzI1N...')
    ->end(function ($err, $res) {
        // ...
    });
```

#### end(callback)

The request is dispatched when the `end` method is called, which accepts a callback. Two variables will be passed to the callback, the first one contains any errors that occurs, and the second one is the Response object. Inside the callback, you should put all of your assertions.