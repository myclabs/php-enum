<?php
/**
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace MyCLabs\Enum;

/**
 * Base Enum class
 *
 * Create an enum by implementing this class and adding class constants.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 * @author Julien Zirnheld <julienzirnheld@gmail.com>
 */
abstract class Enum
{
    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Enum description
     *
     * @var string
     */
    protected $description;

    /**
     * Enum string code
     *
     * @var string
     */
    protected $code;

    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    protected static $cache = array();

    #region magical methods

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value)
    {
        if(!self::isCached()) {
            self::setCache();
        }
        if (!$this->isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . get_called_class());
        }
        $className = get_called_class();
        $this->value = $value;
        $this->description = self::$cache[$className]['annotations'][$value]['description'];
        $this->code = self::$cache[$className]['annotations'][$value]['code'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        if(!self::isCached()) {
            self::setCache();
        }
        $className = get_called_class();
        if (array_key_exists($name, self::$cache[$className]['values'])) {
            return new static(self::$cache[$className]['values'][$name]);
        }
        throw new \BadMethodCallException("No static method or enum constant '$name' in class " . $className);
    }

    #endregion

    #region instance methods

    #region public methods

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return mixed
     */
    public function getKey()
    {
        return static::search($this->value);
    }

    /**
     * Compares one Enum with another.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     *
     * @param Enum $enum
     * @return bool True if Enums are equal, false if not equal
     */
    final public function equals(Enum $enum)
    {
        return $this->getValue() === $enum->getValue() && get_called_class() == get_class($enum);
    }

    /**
     * Get description annotation value
     *
     * @return null|string
     */
    public function getDescription()
    {
        if(is_null($this->description)) {
            throw new \BadMethodCallException('No description annotation was set for '.get_called_class().' with name '.$this->getKey());
        }
        return $this->description;
    }

    /**
     * Get code annotation value
     *
     * @return string
     */
    public function getCode()
    {
        if(is_null($this->code)) {
            throw new \BadMethodCallException('No code annotation was set for '.get_called_class().' with name '.$this->getKey());
        }
        return $this->code;
    }


    #endregion public methods

    #region private methods



    #endregion private methods

    #endregion instance methods

    #region static methods

    #region public methods

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return array
     */
    public static function keys()
    {
        return array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values()
    {
        $values = array();

        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray()
    {
        return self::$cache[get_called_class()]['values'];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     *
     * @return bool
     */
    public static function isValid($value)
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * Check if is valid enum key
     *
     * @param $key
     *
     * @return bool
     */
    public static function isValidKey($key)
    {
        $array = static::toArray();

        return isset($array[$key]);
    }

    /**
     * Return key for value
     *
     * @param $value
     *
     * @return mixed
     */
    public static function search($value)
    {
        return array_search($value, static::toArray(), true);
    }

    #endregion public methods

    #region private methods

    /**
     * Check if enum class is cached
     *
     * @return bool
     */
    private static function isCached()
    {
        return array_key_exists(get_called_class(), static::$cache);
    }

    /**
     * Set Cache
     *
     * @return void
     */
    private static function setCache()
    {
        $reflection = new \ReflectionClass(get_called_class());
        $annotations = self::getDescriptionAndCodeAnnotations();
        $values = array(
            'values' => $reflection->getConstants()
        );
        foreach ($annotations['annotations'] as $key => $value) {
            $annotations['annotations'][$values['values'][$key]] = $annotations['annotations'][$key];
            unset($annotations['annotations'][$key]);
        }
        $finalAnnotations = array_merge($values, $annotations);
        static::$cache[get_called_class()] = $finalAnnotations;
    }

    /**
     * Get description and code annotations
     *
     * @return array
     */
    private static function getDescriptionAndCodeAnnotations()
    {
        $constAnnotations = ConstAnnotationsParser::parseAndReturnAnnotations(get_called_class());
        $enumAnnotations = array();
        foreach ($constAnnotations as $key => $value)
        {
            $annotations = array();
            if(array_key_exists('description', $value)) {
                $annotations['description'] = $value['description'];
            } elseif(array_key_exists('Description', $value)){
                $annotations['description'] = $value['Description'];
            } else {
                $annotations['description'] = null;
            }

            if(array_key_exists('code', $value)) {
                $annotations['code'] = $value['code'];
            } elseif(array_key_exists('Code', $value)){
                $annotations['code'] = $value['Code'];
            } else {
                $annotations['code'] = null;
            }
            $enumAnnotations[$key] = $annotations;
        }
        return array('annotations' => $enumAnnotations);
    }

    #endregion private methods

    #endregion static methods

}
