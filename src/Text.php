<?php namespace Dotink\Flourish {

	/**
	 * Provides internationlization and transformation support for strings
	 *
	 * @copyright  Copyright (c) 2007-2011 Will Bond, others
	 * @author     Will Bond           [wb]  <will@flourishlib.com>
	 * @author     Matthew J. Sahagian [mjs] <msahagian@dotink.org>
	 *
	 * @license    Please see the LICENSE file at the root of this distribution
	 *
	 * @package    Flourish
	 */

	class Text
	{
		const CLASS_LOCALE   = 'en_us';
		const DEFAULT_DOMAIN = 'messages';
		const RIGHT_TO_LEFT  = FALSE;

		/**
		 * Cache for plural <-> singular and underscore <-> camelcase
		 *
		 * This should be overloaded by child translation classes.
		 *
		 * @static
		 * @access private
		 * @var array
		 */
		static protected $cache = [
			'camelize'     => [0 => array(), 1 => array()],
			'humanize'     => array(),
			'pluralize'    => array(),
			'singularize'  => array(),
			'underscorize' => array()
		];


		/**
		 * RegEx responsible for splitting off the last word of a camelcase string
		 *
		 * This should be overloaded by child translation classes.  This one handles 'en_US' words.
		 *
		 * @static
		 * @access protected
		 * @var string
		 */
		static protected $camelCaseSplitRX = '/
			(.*)(
				  (?<=[a-zA-Z_]|^)(?:[0-9]+|[A-Z][a-z]*)
				| (?<=[0-9A-Z_]|^)(?:[A-Z][a-z]*)
			)$
		/xD';


		/**
		 * Rules for camelizing difficult words
		 *
		 * These should be overloaded by child translation classes.  These handle 'en_US' words.
		 *
		 * @static
		 * @access protected
		 * @var array
		 */
		static protected $camelizeRules = array();


		/**
		 * RegEx responsible for matching normal word distinction in camelcase strings
		 *
		 * This should be overloaded by child translation classes.  This one handles 'en_US' words.
		 *
		 *
		 * Description: Underscore before capital letters following lowercase
		 *
		 * @static
		 * @access protected
		 * @var string
		 */
		static protected $camelUnderscoreWordRX = '/(?<=[a-z])([A-Z])/';


		/**
		 * RegEx responsible for matching acronym distinction in camelcase strings
		 *
		 * This should be overloaded by child translation classes.  This one handles 'en_US' words.
		 *
		 * Description: Underscore between uppercase letters if the second letter is followed by
		 * any lowercae letter besides s, or s followed by a non s.  This handles most acronym
		 * cases, whereby an acronym followed by 's' implies it's a plural acronym, however, one
		 * followed by s + another letter implies the last capital letter was part of a new word.
		 *
		 * @static
		 * @access protected
		 * @var string
		 */
		static protected $camelUnderscoreAcronymRX = '/(?<=[A-Z])([A-Z])(?=([a-rt-z]|s[a-z]))/';

		/**
		 * The final join separator, will be spaced and in place of standard separator
		 *
		 * @static
		 * @access protected
		 * @var string
		 */
		static protected $finalJoinSeparator = 'and';


		/**
		 * The join separator
		 *
		 * @static
		 * @access protected
		 * @var string
		 */
		static protected $joinSeparator = ', ';


		/**
		 * Rules for plural to singular inflection of nouns
		 *
		 * These should be overloaded by child translation classes. These handle 'en_US' words.
		 *
		 * @static
		 * @access protected
		 * @var array
		 */
		static protected $pluralToSingularRules = [
			'([ml])ice$'                   => '\1ouse',
			'(media|info(rmation)?|news)$' => '\1',
			'(q)uizzes$'                   => '\1uiz',
			'(c)hildren$'                  => '\1hild',
			'(p)eople$'                    => '\1erson',
			'(m)en$'                       => '\1an',
			'((?!sh).)oes$'                => '\1o',
			'((?<!o)[ieu]s|[ieuo]x)es$'    => '\1',
			'([cs]h)es$'                   => '\1',
			'(ss)es$'                      => '\1',
			'([aeo]l)ves$'                 => '\1f',
			'([^d]ea)ves$'                 => '\1f',
			'(ar)ves$'                     => '\1f',
			'([nlw]i)ves$'                 => '\1fe',
			'([aeiou]y)s$'                 => '\1',
			'([^aeiou])ies$'               => '\1y',
			'(la)ses$'                     => '\1s',
			'(.)s$'                        => '\1'
		];


		/**
		 * A list of single digits and their translations
		 *
		 * These should be overloaded by child translation classes.  These handle 'en_US' words.
		 *
		 * @static
		 * @access protected
		 * @var array
		 */
		static protected $singleDigitTranslations = [
			0 => 'zero',
			1 => 'one',
			2 => 'two',
			3 => 'three',
			4 => 'four',
			5 => 'five',
			6 => 'six',
			7 => 'seven',
			8 => 'eight',
			9 => 'nine'
		];


		/**
		 * Rules for singular to plural inflection of nouns
		 *
		 * This array can actually contain multiple arrays for translations depending on size of
		 * the set in question.  Array keys should be numeric.  If pluralize() is called with
		 * an $n argument, it will reverse sort the numeric keys and find those rules which apply
		 * based on whether the $n argument is greater than or equal to the key.
		 *
		 * These should be overloaded by child translation classes.  These handle 'en_US' words.
		 *
		 * @static
		 * @access protected
		 * @var array
		 */
		static protected $singularToPluralRules = [
			2 => [
				'([ml])ouse$'                  => '\1ice',
				'(media|info(rmation)?|news)$' => '\1',
				'(phot|log|vide)o$'            => '\1os',
				'^(q)uiz$'                     => '\1uizzes',
				'(c)hild$'                     => '\1hildren',
				'(p)erson$'                    => '\1eople',
				'(m)an$'                       => '\1en',
				'([ieu]s|[ieuo]x)$'            => '\1es',
				'([cs]h)$'                     => '\1es',
				'(ss)$'                        => '\1es',
				'([aeo]l)f$'                   => '\1ves',
				'([^d]ea)f$'                   => '\1ves',
				'(ar)f$'                       => '\1ves',
				'([nlw]i)fe$'                  => '\1ves',
				'([aeiou]y)$'                  => '\1s',
				'([^aeiou])y$'                 => '\1ies',
				'([^o])o$'                     => '\1oes',
				's$'                           => 'ses',
				'(.)$'                         => '\1s'
			]
		];


		/**
		 * Rules for underscorizing difficult words
		 *
		 * These should be overloaded by child translation classes.  These handle 'en_US' words.
		 *
		 * @static
		 * @access protected
		 * @var array
		 */
		static protected $underscorizeRules = array();


		/**
		 * RegEx responsible for matching the beginning of words in underscore notation
		 *
		 * This should be overloaded by child translation classes.  This one handles 'en_US' words.
		 *
		 * @static
		 * @access protected
		 * @var string
		 */
		static protected $underscoreWordRX = '#_([a-z0-9])#i';


		/**
		 * Callbacks for when messages are composed
		 *
		 * @static
		 * @access private
		 * @var array
		 */
		static private $composeCallbacks = [
			'pre'  => NULL,
			'post' => NULL
		];


		/**
		 * The default locale for new Text objects, this class handles 'en_US' automatically
		 *
		 * @static
		 * @access private
		 * @var string
		 */
		static private $defaultLocale = self::CLASS_LOCALE;


		/**
		 * Classes for translating different locales, this class handles 'en_US' automatically
		 *
		 * @static
		 * @access private
		 * @var array
		 */
		static private $localeClasses = array();


		/**
		 * The values of the object, translation classes can access these directly
		 *
		 * @access protected
		 * @var array
		 */
		protected $values = array();


		/**
		 * Creates a new text object with a specific locale
		 *
		 * @static
		 * @access public
		 * @param mixed $values The value(s) to use for the text
		 * @param string $locale The locale for the provided values
		 * @return A new text object suitable for the requested locale
		 */
		static public function create($values, $locale = NULL)
		{
			$locale = !$locale
				? static::$defaultLocale
				: strtolower($locale);

			if ($locale == self::CLASS_LOCALE) {

				//
				// If the locale is the one we handle, just set us as the translation class
				// and be done with it
				//

				return new self($values);

			} elseif (isset(self::$localeClasses[$locale])) {

				//
				// If, on the other hand, we have a registered locale class for it, use that
				//

				$translation_class = self::$localeClasses[$locale];

				if (!is_subclass_of($translation_class, __CLASS__)) {
					throw new ProgrammerException(
						'Cannot create text with locale "%s", invalid translation class "%s"',
						$locale,
						$translation_class
					);
				}

				return $translation_class($text);
			}

			//
			// Fail miserably
			//

			throw new ProgrammerException(
				'Cannot create text with locale "%s", no translation class available',
				$locale
			);
		}


		/**
		 * Maps a translation class to a locale
		 *
		 * @static
		 * @access public
		 * @param string $class The class to map
		 * @param string $locale The locale to map the class to
		 * @return void
		 */
		static public function mapClassToLocale($class, $locale)
		{
			self::$localeClasses[strtolower($locale)] = $class;
		}


		/**
		 * Adds a callback for when a message is created using ::compose()
		 *
		 * The primary purpose of these callbacks is for internationalization of
		 * error messaging in Flourish. The callback should accept a single
		 * parameter, the message being composed and should return the message
		 * with any modifications.
		 *
		 * The timing parameter controls if the callback happens before or after
		 * the actual composition takes place, which is simply a call to
		 * [http://php.net/sprintf sprintf()]. Thus the message passed `'pre'`
		 * will always be exactly the same, while the message `'post'` will include
		 * the interpolated variables. Because of this, most of the time the `'pre'`
		 * timing should be chosen.
		 *
		 * @param string $timing When the callback should be executed - `'pre'` or `'post'`
		 * @param callback $callback The callback
		 * @return void
		 */
		static public function registerComposeCallback($timing, $callback)
		{
			$valid_timings = ['pre', 'post'];

			if (!in_array($timing, $valid_timings)) {
				throw new ProgrammerException(
					'The timing specified, %1$s, is not a valid timing. Must be one of: %2$s.',
					$timing,
					join(', ', $valid_timings)
				);
			}

			if (is_string($callback) && strpos($callback, '::') !== FALSE) {
				$callback = explode('::', $callback);
			}

			$this->composeCallbacks[$timing] = $callback;
		}


		/**
		 * Sets the default locale for the Text class
		 *
		 * If the default locale for the class is changed you must have a translation class
		 * mapped to handle that locale.  This class only handles 'en_US' locale.
		 *
		 * @static
		 * @access public
		 * @param string $locale The locale to set as the default
		 * @return void
		 */
		static public function setDefaultLocale($locale)
		{
			self::$defaultlocale = strtolower($locale);
		}


		/**
		 * Splits the last word off of a `camelCase` or `underscore_notation` string
		 *
		 * @param string $string The string to split the word from
		 * @return array Beginning part of string as first element of array, last word as second
		 */
		static protected function splitLastWord($string)
		{
			//
			// Handle strings with spaces in them
			//

			if (strpos($string, ' ') !== FALSE) {
				return [
					substr($string, 0, strrpos($string, ' ') + 1),
					substr($string, strrpos($string, ' ') + 1)
				];
			}

			//
			// Handle underscore notation
			//

			if ($string == (string) (new static($string))->underscorize()) {
				if (strpos($string, '_') === FALSE) {
					return array('', $string);
				}

				return [
					substr($string, 0, strrpos($string, '_') + 1),
					substr($string, strrpos($string, '_') + 1)
				];
			}

			//
			// Handle camel case
			//

			if (preg_match(static::$camelCaseSplitRX, $string, $match)) {
				return [$match[1], $match[2]];
			}

			return ['', $string];
		}


		/**
		 * Normalizes a value by reducing it to a usable type
		 *
		 * Normalized values include all scalar types, arrays, or objects implementing ArrayAccess
		 * and Traversable.  If an array or object implementing ArrayAccess is passed and it's
		 * count value is equal to one, normalization will recurse on the sole item.
		 *
		 * @static
		 * @access private
		 * @param mixed $value The value to normalize
		 * @return mixed The normalized value
		 */
		static private function normalizeValue($value)
		{
			if (!is_object($value)) {
				if (is_array($value)) {
					return count($value) == 1 ? self::normalizeValue($value[0]) : $value;
				}

				//
				// Make scalar data traversable
				//

				return [$value];

			} else {
				if ($value instanceof ArrayAccess) {
					if (count($value) == 1) {
						return self::normalizeValue($value[0]);
					} elseif ($value instanceof Traversable) {
						return $value;
					}
				} elseif (is_callable([$value, '__toString'])) {
					return self::normalize((string) $value);
				}
			}

			throw new fProgrammerException(
				'Invalid value passed to %s operation',
				__CLASS__
			);
		}


		/**
		 * Constructs a new text object
		 *
		 * @access public
		 * @param mixed $values The value(s) to use for the text
		 * @return void
		 */
		public function __construct($value = NULL)
		{
			$this->values = self::normalizeValue($value);
		}


		/**
		 * Translates the Text object to a string, by default this joins with 'and'
		 *
		 * @access public
		 * @return string The object converted to a string
		 */
		public function __toString()
		{
			return $this->join()->values[0];
		}


		/**
		 * Gets a new Text object whose value(s) is the camelCase form of the original's
		 *
		 * @access public
		 * @param boolean $upper Whether or not the string value(s) should be `UpperCamelCase`
		 * @return Text A new text object whose value(s) has been humanized
		 */
		public function camelize($upper = FALSE)
		{
			$new   = clone $this;
			$upper = (int)(bool)$upper;

			foreach ($this->values as $i => $string) {
				if (isset(static::$cache['camelize'][$upper][$string])) {
					$new->values[$i] = static::$cache['camelize'][$upper][$string];
					continue;
				}

				$original = $string;

				if (isset(static::$camelizeRules[$string])) {
					$string = static::$camelizeRules[$string];

					if ($upper) {
						$string = UTF8::ucfirst($string);
					}

				} else {

					//
					// Make humanized string with spaces similar to underscore notation
					//

					if (strpos($string, ' ') !== FALSE) {
						$string = UTF8::lower(preg_replace('#\s+#', '_', $string));
					}

					//
					// See if we're already camelized
					//

					if (strpos($string, '_') === FALSE) {
						if ($upper) {
							$string = UTF8::ucfirst($string);
						}

					} else {
						$string[0] = UTF8::lower($string[0]);

						if ($upper) {
							$string = UTF8::ucfirst($string);
						}

						$string = preg_replace_callback(
							static::$underscoreWordRX,
							function($match) {
								return UTF8::upper($match[1]);
							},
							$string
						);
					}
				}

				$new->values[$i] = static::$cache['camelize'][$upper][$original] = $string;
			}

			return $new;
		}


		/**
		 * Performs an [http://php.net/sprintf sprintf()] on text values via registered hooks
		 *
		 * This is predominately used for translation.  The callback will receive the values of
		 * the Text object as well as the provided domain.  If the value of the text object is
		 * a string, users will most likely just want to run through gettext or something similar.
		 *
		 * Pre callbacks may be operating on arrays or array access objects.  These should be
		 * treated as a standard join in the target language.  This provides for the ability, for
		 * example, to reverse the array in non-left-to-right languages.
		 *
		 * @access public
		 * @param string $domain The domain of the message (can be used for modular components)
		 * @param mixed $component A string or number to insert into the message
		 * @param mixed ...
		 * @return string The composed message
		 */
		public function compose($domain = NULL)
		{
			$components = array_slice(func_get_args(), 1);
			$value      = count($this->values) == 1 ? $this->values[0] : $this->values;

			if (count($components) == 1 && is_array($components[0])) {
				$components = $components[0];
			}

			if ($domain == NULL) {
				$domain = static::DEFAULT_DOMAIN;
			}

			if (isset(self::$composeCallbacks['pre'])) {

				$callback = self::$composeCallbacks['pre'];

				//
				// Handles components passed as an array
				//

				if (count($components) == 1 && is_array($components[0])) {
					$components = $components[0];
				}

				$value = call_user_func($callback, $value, $domain);

				foreach ($components as $i => $component) {
					$components[$i] = call_user_func($callback, $component, $domain);
				}
			}

			if (count($value) == 1) {
				$value = vsprintf($value, $components);
			}

			return (isset(self::$composeCallbacks['post']))
				? call_user_func($callback, $value, $domain)
				: $value;
		}


		/**
		 * Gets a new Text object whose value(s) is the human form of the original
		 *
		 * @access public
		 * @return Text A new text object whose value(s) has been humanized
		 */
		public function humanize()
		{
			$new = clone $this;

			foreach ($this->values as $i => $string) {
				if (isset(static::$cache['humanize'][$string])) {
					$new->values[$i] = static::$cache['humanize'][$string];
					continue;
				}

				$original = $string;

				//
				// Need to implement some functionality
				//

				$new->values[$i] = static::$cache['humanize'][$original] = $string;
			}

			return $new;
		}


		/**
		 * Gets a new Text object whose value is the singular or plural form based on quantity
		 * the current one's values.
		 *
		 * @param string $singular_form The string to be returned for when `$quantity = 1`
		 * @param string $plural_form The string to be returned for when `$quantity != 1`, use `%d` to place the quantity in the string
		 * @param boolean $single_digit_words If the numbers 0 to 9 should be written out as words
		 * @return Text A new text object whose value is the inflected word
		 */
		public function inflectOnQuantity($singular, $plural= NULL, $single_digit_words = FALSE)
		{
			$quantity = count($this->values);
			$singular = new static($singular);

			if ($quantity == 1) {
				return $singular;

			} elseif ($plural === NULL) {
				return $singular->pluralize($quantity);

			} else {

				//
				// Handle placement of the quantity into the output
				//

				if (strpos($plural, '%d') !== FALSE) {

					$replacement = $quantity;

					if ($single_digit_words && $quantity < 10) {

						if (isset(static::$singleDigitTranslations[$quantity])) {
							$replacement = static::$singleDigitTranslations[$quantity];
							$replacement = (new static($replacement))->compose();
						}
					}

					$plural = str_replace('%d', $replacement, $plural);
				}

				return new static($plural);
			}
		}


		/**
		 * Gets a new Text object whose value is the values of the current object joined
		 *
		 * @param string $separator The separator to use, comma + space by default
		 * @param string $final_separator The final separator, this can be more wordy
		 * @return Text A new text object whose value is the joined string
		 */
		public function join($separator = NULL, $final_separator = NULL)
		{
			$new = clone $this;

			$separator = ($separator === NULL)
				? static::$joinSeparator
				: $separator;

			$final_separator = ($final_separator === NULL)
				? static::$finalJoinSeparator
				: $final_separator;

			if (static::RIGHT_TO_LEFT) {
				array_reverse($new->values);
			}

			switch (count($new->values)) {
				case 0:
				case 1:
					break;

				case 2:
					$new->values = [
						$new->value[0] . ' ' . $final_separator . ' ' . $new->values[1]
					];

					break;

				default:
					$end_value   = array_pop($new->values);
					$new->values = [
						join($separator, $new->values) . ' ' . $final_separator . ' ' . $end_value
					];

					break;
			}

			return $new;
		}


		/**
		 * Makes the value of the Text object plural
		 *
		 * @param integer $quantity The number of items in the plural set
		 * @param boolean $return_false_on_error If TRUE, returns FALSE in the event of an error
		 * @return Text A new Text object whose values are the plural versions of this one's
		 */
		public function pluralize($quantity = 2, $return_false_on_error = FALSE)
		{
			$new = clone $this;

			if (is_array($quantity) || $quantity instanceof Countable) {
				$quantity = count($quantity);
			}

			foreach ($new->values as $i => $string) {
				if (isset(static::$cache['pluralize'][$string])) {
					$new->values[$i] = static::$cache['pluralize'][$string];
					continue;
				}

				$original                 = $string;
				$plural_string            = NULL;
				list($beginning, $string) = static::splitLastWord($string);
				$set_sizes                = array_keys(static::$singularToPluralRules);

				sort($set_sizes);

				foreach (array_reverse($set_sizes) as $set_size) {
					if ($quantity >= $set_size) {
						$rules = static::$singularToPluralRules[$set_size];

						break;
					}
				}

				foreach ($rules as $from => $to) {
					$regex = '#' . $from . '#iD';

					if (preg_match($regex, $string)) {
						$plural_string = $beginning . preg_replace($regex, $to, $string);
					}
				}

				if (!$plural_string) {
					if ($return_false_on_error) {
						static::$cache['pluralize'][$string] = NULL;
						return FALSE;
					}

					throw new ProgrammerException(
						'The string "%s" could not be pluralized',
						$string
					);
				}

				$new->values[$i] = static::$cache['pluralize'][$string] = $plural_string;
			}

			return $new;
		}


		/**
		 * Makes the values of the Text object singular
		 *
		 * @param boolean $return_false_on_error If TRUE, returns FALSE in the event of an error
		 * @return Text A new Text object, whose values are the singular versions of the this one's
		 */
		public function singularize($return_false_on_error = FALSE)
		{
			$new = clone $this;

			foreach ($new->values as $i => $string) {
				if (isset(static::$cache['singularize'][$string])) {
					$new->values[$i] = static::$cache['singularize'][$string];
				}

				$original        = $string;
				$singular_string = NULL;

				list($beginning, $string) = static::splitLastWord($string);

				foreach (static::$pluralToSingularRules as $from => $to) {
					$regex = '#' . $from . '#iD';

					if (preg_match($regex, $string)) {
						$singular_string = $beginning . preg_replace($regex, $to, $string);
					}
				}

				if (!$singular_string) {
					if ($return_false_on_error) {
						static::$cache['singularize'][$string] = NULL;
						return FALSE;
					}

					throw new ProgrammerException(
						'The string "%s" could not be singularized',
						$string
					);
				}

				$new->values[$i] = static::$cache['singularize'][$string] = $singular_string;
			}

			return $new;
		}


		/**
		 * Converts a `camelCase`, human-friendly or `underscore_notation` string to
		 * `underscore_notation`
		 *
		 * This will use the $camelUnderscoreWordRX and the $camelUnderscoreAcronymRX variables
		 * for the tranlation class and place an underscore before the second match.
		 *
		 * @param  string $string  The string to convert
		 * @return string The converted string
		 */
		public function underscorize()
		{
			$new = clone $this;

			foreach ($new->values as $i => $string) {

				if (isset(static::$cache['underscorize'][$string])) {
					$new->values[$i] = static::$cache['underscorize'][$string];
					continue;
				}

				$original = $string;

				if (isset(static::$underscorizeRules[$string])) {

					//
					// Handle custom rules
					//

					$string = static::$underscorizeRules[$string];

				} elseif (strpos($string, ' ') !== FALSE) {

					//
					// Allow humanized strings to be passed in
					//

					$string = UTF8::lower(preg_replace('#\s+#', '_', $string));

				} elseif (UTF8::lower($string) != $string) {
					$string = preg_replace(static::$camelUnderscoreWordRX, '_$1', $string);
					$string = preg_replace(static::$camelUnderscoreAcronymRX, '_$1', $string);
					$string = UTF8::lower($string);
				}

				$new->values[$i] = static::$cache['underscorize'][$original] = $string;
			}

			return $new;
		}
	}
}
