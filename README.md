# PHP Validator Class `v1.0`
This is a simple PHP Validator Class. It requires no dependencies. Fork it and have fun with it.

It's a standalone single file PHP class to use on your projects. It requires no dependencies or no framework.

## How To Use
You only need only one file:

 - `Validator.php`

You can run everything from the index.php file, see the file for usage.

### Step 1 - Initialize
Before start
```php
// Include the class file
require "Validator.php";

// Data to validate
$data = [
    "name" => "John Doe",
    "age" => 25,
    "email" => "john@example.com",
    "password" => "pass@123",
    "confirm_password" => "pass@123",
    "sex" => "male",
    "phone" => "1236547895"
];
```

Create the class instance
```php
$v = new Validator($data);
```
Use a associative array as data

### Step 2 - 
Run validation by chaining methods to the `field()` method. [Check here](#methods) to see description of every methods.
```php
$v->field('name')->required()->alpha([' ']);
$v->field('age') ->required()->numeric()->min_val(14)->max_val(100);
$v->field('email')->required()->email();
$v->field('password')->required()->min_len(8)->max_len(16)->must_contain('@#$&')->must_contain('a-z')->must_contain('A-Z')->must_contain('0-9');
$v->field('confirm_password')->required()->equals($data['password']);
$v->field('sex')->enum(['male', 'female', 'others']);
$v->field('phone')->numeric()->min_len(10)->max_len(10);
```
Make sure to run the field method on start of every method chain.

### Step 3 -
Check if data is valid
```php
if(!$v->is_valid()){
    // Print the error messages
    print_r($v->error_messages);
}
```

## Properties
- `array $error_messages` - Get the list of generated error messages.

## Methods
Some methods to use
| Methods | Return | Description |
|--------|--------|-------------|
| `set_response_messages(arr $messages)` | void | Function to set/extend custom error. <br /> Use associative array of messages as the parameter. See the messages format on `Validator.php` file at line `20`.
| is_valid() | boolean | Check if all validations are successfull.

Here is a list of the validators currently available.

| Validator | Description |
| ----------|-------------|
| `required()` | Check if the value exists. |
| `alpha(arr $ignore)` | Check if the value is alpha only. <br/> param *array* `$ignore` - (optional) add charectors to allow. Ex. ['@', ' '] |
| `alpha_num()` | Check if the value is alpha numeric only. <br/> param *array* `$ignore` - (optional) add charectors to allow. Ex. ['@', ' '] |
| `numeric()` | Check if the value is numeric only. |
| `email()` | Check if the value is a valid email. |
| `max_len(int $size)` | Check if length of the value is larger than the limit. <br/> param *int* `$size` - Max length of charectors of the value. |
| `min_len(int $size)` | Check if length of the value is smaller than the limit. <br/> param *int* `$size` - Min length of charectors of the value. |
| `max_val(int $val)` | Check if the value of intiger/number is not larger than the limit. <br/> param *int* `$val` - Max value of the number. |
| `min_val(int $val)` | Check if the value of intiger/number is not smaller than the limit. <br/> param *int* `$val` - Min value of the number. |
| `enum(arr $list)` | Check if the value is in the list. <br/>  param *array* `$list` - List of valid values. |
| `equals(mix $value)` | Check if the value is equal. <br/> param *mixed* `$value` - Value to match equal. |
| `must_contain(str $chars)` | Check if the value must contains some charectors. <br/> param *string* `$chars` - Set of chars in one string. Ex. "@#&abc123"|
| `match(str $pattern)` | Check if the value matchs a pattern. <br/> param *string* `$patarn` - Rejex pattern to match. |

## More
- You can change default error response messages on `Validator.php` at line `20`.
- For the pattern of `match()` method, check out [PHP Manual](https://www.php.net/manual/en/function.preg-match.php) and [W3Schools](https://www.w3schools.com/php/php_regex.asp).

## LICENSE
[MIT License](LICENSE)