Easy Text Transformation
============

Language sensitive text transformation and translation.

The class provided in this package is designed to be used as a factory for additional grammars.
Additionally, this class provides the rules for English grammar and can be used as a template for
other grammars.

Objects of this class are immutable, meaning that they will always return a new text object with
modified values instead of modifying the original.

## Basic Usage

Most functions follow a similar pattern, assume the text below is the same object throughout,
except where noted.  Any text modification should be able to work on text already in any one
of the other existing forms.

```php
$text = Text::create('green back turtle');
```

### Camelize

```php
$text->camelize()->compose() // OUTPUT: greenBackTurtle
```

```php
$text->camelize(TRUE)->compose() // OUTPUT: GreenBackTurtle
```

### Underscorize

```php
$text->underscorize()->compose() // OUTPUT: green_back_turtle
```

### Dashize

```php
$text->dashize()->compose() // OUTPUT: green-back-turtle
```

### Pluralize / Singularize

```php
$text->pluralize()->compose() // OUTPUT: green back turtles
```

Using the pluralized text object from above we could do:

```php
$text->singularize()->compose() // OUTPUT: green back turtle
```

### Humanize

```php
$text->humanize()->compose() // OUTPUT: Green back turtle
```

```php
$text->humanize(TRUE)->compose() // OUTPUT: Green Back Turtle
```

## Multi Values

You can assign arrays of values to text objects for other types of operations.  Any of the
aforementioned transformations will affect all values in an array.

```php
$text = Text::create(['turtle', 'mouse', 'lion']);
```

Using an array you can additionally perform actions such as the following.

### Join

```php
$text->join()->compose() // OUTPUT: turtle, mouse, and lion
```

### Quantity Inflection

```php
$text->inflect('There is %d animal', 'There are %d animals')->compose() // OUTPUT: There are 3 animals
```
