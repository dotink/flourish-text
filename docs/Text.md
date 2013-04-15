# Text

The `Text` class is responsible for internationalization and grammatical translation of text strings.  By default it handles English strings.

## Encapsulation

Any number of additional classes can be loaded to handle text operations on non-English strings.  This is useful if you're doing your development in a different language or if you need to handle operations on alternative language strings.  The `Text` object should be instantiated using the static `create()` method which will allow for using a registered subclass for various locales.

```php
$sentence = Text::create('This is a sentence using English Grammar');
```

## Multiple Values

Any `Text` object can contain multiple text values to be operated on at any given point.  For example, the following would allow operations to execute across all words at once.

```php
$animals = Text::create(['lion', 'tiger', 'bear']);
$animals = $animals->pluralize();

echo $animals->join();
```

**Output**

```
lions, tigers and bears
```

## Grammatical Operations

Various methods are provided for a number of standard grammatical operations, but also for those that are more relevant for code.  Each operation will act on all members of a `Text` object.

### Underscorize

The `underscorize()` method converts all members of the text object to underscore notation while attempting to respect acronyms.

```php
$url_method = Text::create('myLowerCamelcaseURLMethod');
$url_method = $url_method->underscorize();

echo $url_method->compose();
```

**Output**

```
my_lower_camelcase_url_method
```

### Camelize

```
$methods = Text::create(['get_key', 'create_text_from_garbage', 'consume']);
$methods = $methods->camelize();

echo $methods->join();
```

**Output**

```
getKey, createTextFromGarbage and consume
```

You can trigger UpperCamelCase notation by passing `TRUE` to the camelize method.

```
$methods = $methods->camelize(TRUE);

echo $methods->join();
```

**Output**

```
GetKey, CreateTextFromGarbage and Consume
```


### Pluralize

The `pluralize()` method converts all members of the text object to their plural version.

