# Encapsulation

A simple way to accommodate, exchange and structorize data with objects. This library provides a set of tools to create encapsulated data objects and type-safe collections in PHP.

## Installation

You can install the package via composer:

```bash
composer require xchert/encapsulation
```

## Features

- **Property Encapsulation**: Map arrays to object properties with support for getters, setters, and reflection-based access.
- **Array Encapsulation**: Wrap arrays in objects with field validation and controlled access.
- **Type-safe Containers**: Collections that can be restricted to specific classes, supporting common array operations (map, filter, reduce, etc.).
- **Immutability**: Read-only versions (`EncapsulatedProperties`, `EncapsulatedArray`, `Container`) and mutable versions (`PropertyEncapsulation`, `ArrayEncapsulation`, `MutableContainer`) are available.
- **Magic Methods & ArrayAccess**: Support for `__get`, `__set`, `__isset`, `__unset` and `ArrayAccess` for intuitive object interaction.
- **Iteration & Serialization**: All encapsulated objects and containers are iterable and JSON serializable.

## Interfaces & Methods

Most classes in this library implement either `Encapsulated` (read-only) or `Encapsulation` (mutable) interfaces.

#### Encapsulated Interface

Provides read access to data.

- `get(string $field)`: Retrieve a field value. Returns `null` if not set.
- `getList(array $fields)`: Retrieve multiple fields as an associative array.
- `has(string $field)`: Check if a field exists.
- `toArray()`: Export all data as an array.
- `getFields()`: Get a list of all defined field names.
- `isEmpty()`: Check if the object contains any data.

#### Encapsulation Interface

Extends `Encapsulated` to provide write access.

- `set(string $field, mixed $value)`: Set a field value.
- `setList(array $data)`: Set multiple fields from an associative array.
- `unset(string $field)`: Remove a field.
- `add(string $field, mixed $value)`: Append a value to a field.
    - If the field doesn't exist, it's initialized as an array containing the value.
    - If the field is an array, the value is appended.
    - If the field is a `MutableContainer`, the value is added to it.
    - Otherwise, a `NotAddableException` is thrown.
- `addList(string $field, array $values)`: Append multiple values to a field.

## Usage

### Property Encapsulation

Use `PropertyEncapsulation` to create data objects where properties are automatically populated and can be accessed/modified using `get()` and `set()`.

```php
use Xchert\Encapsulation\PropertyEncapsulation;

class BlogPost extends PropertyEncapsulation
{
    protected string $title;
    protected string $content;
    protected ?string $author = null;
}

$post = new BlogPost();
$post->set('title', 'My First Post');
$post->setList([
    'content' => 'Hello World!',
    'author' => 'John Doe'
]);

echo $post->get('title'); // My First Post
echo $post->title; // Magic get also works!
$post['title'] = 'New Title'; // ArrayAccess support

print_r($post->toArray());
```

For read-only objects, extend `EncapsulatedProperties`:

```php
use Xchert\Encapsulation\EncapsulatedProperties;

class ReadOnlyUser extends EncapsulatedProperties
{
    protected string $username;
    protected string $email;
}

$user = new ReadOnlyUser(['username' => 'jdoe', 'email' => 'jdoe@example.com']);
echo $user->get('username');
// $user->set('username', 'newname'); // Not available
```

### Array Encapsulation

`ArrayEncapsulation` provides an object-oriented way to work with arrays, including field validation. You can use it directly for generic data or extend it to restrict allowed fields.

#### Direct Use

```php
use Xchert\Encapsulation\ArrayEncapsulation;
use Xchert\Encapsulation\EncapsulatedArray;

// Mutable array encapsulation
$data = new ArrayEncapsulation();
$data->set('name', 'Junie');
$data->add('tags', 'ai');
$data->add('tags', 'helper');

echo $data->get('name'); // Junie
print_r($data->get('tags')); // ['ai', 'helper']

// Read-only array encapsulation
$readOnly = new EncapsulatedArray(['version' => '1.0.0', 'status' => 'stable']);
echo $readOnly->get('version'); // 1.0.0
```

#### Extending for Validation

```php
use Xchert\Encapsulation\ArrayEncapsulation;

class Configuration extends ArrayEncapsulation
{
    public function isFieldAllowed(string $field): bool
    {
        return in_array($field, ['host', 'port', 'timeout']);
    }
}

$config = new Configuration();
$config->set('host', 'localhost');
// $config->set('invalid_key', 'value'); // Throws NotAllowedFieldException
```

### Containers

`Container` and `MutableContainer` are powerful collection classes.

```php
use Xchert\Encapsulation\Container;
use Xchert\Encapsulation\MutableContainer;

// Simple container
$container = new Container([1, 2, 3]);
$newContainer = $container->map(fn($n) => $n * 2); // Container([2, 4, 6])

// Type-safe container
class BlogPostContainer extends MutableContainer
{
    protected function getAllowedClass(): ?string
    {
        return BlogPost::class;
    }
}

$posts = new BlogPostContainer();
$posts->add(new BlogPost());
// $posts->add("Not a blog post object"); // Throws InvalidArgumentException
```
