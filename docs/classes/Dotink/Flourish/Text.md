# Text
## Provides internationlization and transformation support for strings

_Copyright (c) 2007-2015 Will Bond, Matthew J. Sahagian, others_.
_Please reference the LICENSE.md file at the root of this distribution_

#### Namespace

`Dotink\Flourish`

#### Authors

<table>
	<thead>
		<th>Name</th>
		<th>Handle</th>
		<th>Email</th>
	</thead>
	<tbody>
	
		<tr>
			<td>
				Will Bond
			</td>
			<td>
				wb
			</td>
			<td>
				will@flourishlib.com
			</td>
		</tr>
	
		<tr>
			<td>
				Matthew J. Sahagian
			</td>
			<td>
				mjs
			</td>
			<td>
				msahagian@dotink.org
			</td>
		</tr>
	
	</tbody>
</table>

## Properties
### Static Properties
#### <span style="color:#6a6e3d;">$cache</span>

Cache for plural <-> singular and underscore <-> camelcase

##### Details

This should be overloaded by child translation classes.

#### <span style="color:#6a6e3d;">$camelAcronymRX</span>

RegEx responsible for matching acronym distinction in camelcase strings

##### Details

This should be overloaded by child translation classes.  This one handles 'en_US' words.

#### <span style="color:#6a6e3d;">$camelCaseSplitRX</span>

RegEx responsible for splitting off the last word of a camelcase string

##### Details

This should be overloaded by child translation classes.  This one handles 'en_US' words.

#### <span style="color:#6a6e3d;">$camelizeRules</span>

Rules for camelizing difficult words

##### Details

These should be overloaded by child translation classes.  These handle 'en_US' words.

#### <span style="color:#6a6e3d;">$camelWordRX</span>

RegEx responsible for matching normal word distinction in camelcase strings

##### Details

This should be overloaded by child translation classes.  This one handles 'en_US' words.

#### <span style="color:#6a6e3d;">$dashizeRules</span>

Rules for dashizing difficult words

##### Details

These should be overloaded by child translation classes.  These handle 'en_US' words.

#### <span style="color:#6a6e3d;">$finalJoinSeparator</span>

The final join separator, will be spaced and in place of standard separator

#### <span style="color:#6a6e3d;">$joinSeparator</span>

The join separator

#### <span style="color:#6a6e3d;">$pluralToSingularRules</span>

Rules for plural to singular inflection of nouns

##### Details

These should be overloaded by child translation classes. These handle 'en_US' words.

#### <span style="color:#6a6e3d;">$singleDigitTranslations</span>

A list of single digits and their translations

##### Details

These should be overloaded by child translation classes.  These handle 'en_US' words.

#### <span style="color:#6a6e3d;">$singularToPluralRules</span>

Rules for singular to plural inflection of nouns

##### Details

This array can actually contain multiple arrays for translations depending on size of
the set in question.  Array keys should be numeric.  If pluralize() is called with
an $n argument, it will reverse sort the numeric keys and find those rules which apply
based on whether the $n argument is greater than or equal to the key.

These should be overloaded by child translation classes.  These handle 'en_US' words.

#### <span style="color:#6a6e3d;">$titleArticlesRX</span>

#### <span style="color:#6a6e3d;">$underscorizeRules</span>

Rules for underscorizing difficult words

##### Details

These should be overloaded by child translation classes.  These handle 'en_US' words.

#### <span style="color:#6a6e3d;">$underscoreWordRX</span>

RegEx responsible for matching the beginning of words in underscore notation

##### Details

This should be overloaded by child translation classes.  This one handles 'en_US' words.

#### <span style="color:#6a6e3d;">$composeCallbacks</span>

Callbacks for when messages are composed

#### <span style="color:#6a6e3d;">$defaultLocale</span>

The default locale for new Text objects, this class handles 'en_US' automatically

#### <span style="color:#6a6e3d;">$localeClasses</span>

Classes for translating different locales, this class handles 'en_US' automatically



### Instance Properties
#### <span style="color:#6a6e3d;">$values</span>

The values of the object, translation classes can access these directly




## Methods
### Static Methods
<hr />

#### <span style="color:#3e6a6e;">create()</span>

Creates a new text object with a specific locale

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$values
			</td>
			<td>
									<a href="http://php.net/language.pseudo-types">mixed</a>
				
			</td>
			<td>
				The value(s) to use for the text
			</td>
		</tr>
					
		<tr>
			<td>
				$locale
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The locale for the provided values
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			A
		</dt>
		<dd>
			new text object suitable for the requested locale
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">mapClassToLocale()</span>

Maps a translation class to a locale

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$class
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The class to map
			</td>
		</tr>
					
		<tr>
			<td>
				$locale
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The locale to map the class to
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			void
		</dt>
		<dd>
			Provides no return value.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">registerComposeCallback()</span>

Adds a callback for when a message is created using ::compose()

##### Details

The primary purpose of these callbacks is for internationalization of
error messaging in Flourish. The callback should accept a single
parameter, the message being composed and should return the message
with any modifications.

The timing parameter controls if the callback happens before or after
the actual composition takes place, which is simply a call to
[http://php.net/sprintf sprintf()]. Thus the message passed `'pre'`
will always be exactly the same, while the message `'post'` will include
the interpolated variables. Because of this, most of the time the `'pre'`
timing should be chosen.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$timing
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				When the callback should be executed - `'pre'` or `'post'`
			</td>
		</tr>
					
		<tr>
			<td>
				$callback
			</td>
			<td>
									callback				
			</td>
			<td>
				The callback
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			void
		</dt>
		<dd>
			Provides no return value.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">setDefaultLocale()</span>

Sets the default locale for the Text class

##### Details

If the default locale for the class is changed you must have a translation class
mapped to handle that locale.  This class only handles 'en_US' locale.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$locale
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The locale to set as the default
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			void
		</dt>
		<dd>
			Provides no return value.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">splitLastWord()</span>

Splits the last word off of a `camelCase` or `underscore_notation` string

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$string
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The string to split the word from
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			array
		</dt>
		<dd>
			Beginning part of string as first element of array, last word as second
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">normalizeValue()</span>

Normalizes a value by reducing it to a usable type

##### Details

Normalized values include all scalar types, arrays, or objects implementing ArrayAccess
and Traversable.  If an array or object implementing ArrayAccess is passed and it's
count value is equal to one, normalization will recurse on the sole item.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$value
			</td>
			<td>
									<a href="http://php.net/language.pseudo-types">mixed</a>
				
			</td>
			<td>
				The value to normalize
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			mixed
		</dt>
		<dd>
			The normalized value
		</dd>
	
</dl>




### Instance Methods
<hr />

#### <span style="color:#3e6a6e;">__construct()</span>

Constructs a new text object

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$values
			</td>
			<td>
									<a href="http://php.net/language.pseudo-types">mixed</a>
				
			</td>
			<td>
				The value(s) to use for the text
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			void
		</dt>
		<dd>
			Provides no return value.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">__toString()</span>

Translates the Text object to a string, by default this joins with 'and'

###### Returns

<dl>
	
		<dt>
			string
		</dt>
		<dd>
			The object converted to a string
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">camelize()</span>

Gets a new Text object whose value(s) is the camelCase form of the original's

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$upper
			</td>
			<td>
									<a href="http://php.net/language.types.boolean">boolean</a>
				
			</td>
			<td>
				Whether or not the string value(s) should be `UpperCamelCase`
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			Text
		</dt>
		<dd>
			A new text object whose value(s) has been humanized
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">compose()</span>

Performs an [http://php.net/sprintf sprintf()] on text values via registered hooks

##### Details

This is predominately used for translation.  The callback will receive the values of
the Text object as well as the provided domain.  If the value of the text object is
a string, users will most likely just want to run through gettext or something similar.

Pre callbacks may be operating on arrays or array access objects.  These should be
treated as a standard join in the target language.  This provides for the ability, for
example, to reverse the array in non-left-to-right languages.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$domain
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The domain of the message (can be used for modular components)
			</td>
		</tr>
					
		<tr>
			<td>
				$component
			</td>
			<td>
									<a href="http://php.net/language.pseudo-types">mixed</a>
				
			</td>
			<td>
				A string or number to insert into the message
			</td>
		</tr>
					
		<tr>
			<td>
				...
			</td>
			<td>
									<a href="http://php.net/language.pseudo-types">mixed</a>
				
			</td>
			<td>
				
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			string
		</dt>
		<dd>
			The composed message
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">dashize()</span>


<hr />

#### <span style="color:#3e6a6e;">humanize()</span>

Gets a new Text object whose value(s) is the human form of the original

###### Returns

<dl>
	
		<dt>
			Text
		</dt>
		<dd>
			A new text object whose value(s) has been humanized
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">inflectOnQuantity()</span>

Gets a new Text object whose value is the singular or plural form based on quantity
the current one's values.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$singular_form
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The string to be returned for when `$quantity = 1`
			</td>
		</tr>
					
		<tr>
			<td>
				$plural_form
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The string to be returned for when `$quantity != 1`, use `%d` to place the quantity in the string
			</td>
		</tr>
					
		<tr>
			<td>
				$single_digit_words
			</td>
			<td>
									<a href="http://php.net/language.types.boolean">boolean</a>
				
			</td>
			<td>
				If the numbers 0 to 9 should be written out as words
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			Text
		</dt>
		<dd>
			A new text object whose value is the inflected word
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">join()</span>

Gets a new Text object whose value is the values of the current object joined

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$separator
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The separator to use, comma + space by default
			</td>
		</tr>
					
		<tr>
			<td>
				$final_separator
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The final separator, this can be more wordy
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			Text
		</dt>
		<dd>
			A new text object whose value is the joined string
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">pluralize()</span>

Makes the value of the Text object plural

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$quantity
			</td>
			<td>
									<a href="http://php.net/language.types.integer">integer</a>
				
			</td>
			<td>
				The number of items in the plural set
			</td>
		</tr>
					
		<tr>
			<td>
				$return_false_on_error
			</td>
			<td>
									<a href="http://php.net/language.types.boolean">boolean</a>
				
			</td>
			<td>
				If TRUE, returns FALSE in the event of an error
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			Text
		</dt>
		<dd>
			A new Text object whose values are the plural versions of this one's
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">singularize()</span>

Makes the values of the Text object singular

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$return_false_on_error
			</td>
			<td>
									<a href="http://php.net/language.types.boolean">boolean</a>
				
			</td>
			<td>
				If TRUE, returns FALSE in the event of an error
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			Text
		</dt>
		<dd>
			A new Text object, whose values are the singular versions of the this one's
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">underscorize()</span>

Converts a `camelCase`, human-friendly or `underscore_notation` string to
`underscore_notation`

##### Details

This will use the $camelUnderscoreWordRX and the $camelAcronymRX variables
for the tranlation class and place an underscore before the second match.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$string
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The string to convert
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			string
		</dt>
		<dd>
			The converted string
		</dd>
	
</dl>






