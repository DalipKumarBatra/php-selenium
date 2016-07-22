<?php

/**
 * Utility to help create fixtures in a dynamic, sensical fashion. See
 * wiki entry for DB_Model_Factory for details. It is essentially a
 * factory of factories.
 */
class DB_Model_Factory
{
    use Factory_Hints;

    private static $base_factory = null; // working factory instance
    private static $configured = false; // one-time global configuration allowed

    /**
     * This function is called in factory files ONLY. Calls to configure in test
     * cases will not take.
     *
     */
    public static function configure($conf_closure)
    {
        if (self::$configured) {
            throw new Exception("DB_Model::configure() is only used for the factories directory. Configure your individual test instance of DB_Model_Factory instead. ");
        }
        $conf_closure(self::$base_factory);

        return self::$base_factory;
    }

    /**
     *  Load all global factory configurations defined in test/php/framework/factories (only once)
     */
    public static function configure_once()
    {
        if (self::$configured) return;

        if (!self::$base_factory) {
            self::$base_factory = new DB_Model_Factory();
        }

        // initialize test factories
        foreach (glob('test/php/framework/factories/*.php') as $factory_file) {
            require_once $factory_file;
        }
        self::$configured = true;
    }

    /**
     * @param DB_Model_Factory|DB_Model_Mock_Factory $factory
     * To re-use the same factory definition files between factories and mock_factories,
     * we have to hijack DB_Model_Factory::configure by injecting a different base factory
     */
    public static function set_base_factory($factory)
    {
        self::$base_factory = $factory;
    }

    /**
     * @return null|DB_Model_Factory|DB_Model_Mock_Factory
     */
    public static function get_base_factory()
    {
        return self::$base_factory;
    }

    /**
     * Create an instance of the factory from the base factory
     * Changes to the factory returned affect ONLY that instance
     * @return DB_Model_Factory
     */
    public static function create()
    {
        if (!self::$configured) {
            self::configure_once();
        }

        return self::$base_factory->get_clone();
    }

    private $templates = array(); // this will contain the templates for objects we have
    private $attributes = array(); // this will contain attributes / traits for multiple types of objects
    public $use_db_model_save = true; // enable/disable DB_Model::save()

    /**
     * Produces a clone of the current factory, which can be modified separately from this one.
     * @return DB_Model_Factory
     */
    public function get_clone()
    {
        $cloned = new DB_Model_Factory();
        $cloned->templates = $this->templates;
        $cloned->attributes = $this->attributes;

        return $cloned;
    }

    public function use_db_model_save($set = true)
    {
        $this->use_db_model_save = $set;
    }

    /**
     * Defines a factory for a given type of database object
     *
     * @param string  $name              the name of the factory you are creating. This will be used to create instances later.
     * @param string  $class             the name of the DB_Model class you are creating a factory for.
     * @param Closure $configure_closure actions and values to set on your
     *
     */
    public function define($name, $class, $configure_closure)
    {
        $this->error_if_already_defined($name);
        $template = new DB_Model_Factory_Template($name, $configure_closure, $this);
        $template->class = $class;

        $this->templates[$name] = $template;
    }

    /**
    * Modifies an existing factory - this does NOT create an attribute - it actually
    * changes the closure that executes at this level. Attributes defined on this
    * definition at runtime will execute AFTER the modification has taken place.
    *
    * @param string $name the name of the factory to modify
    * @param Closure $configure_closure the configuration closure that extends
    * the existing factory's configure_closure.
    */
    public function modify($name, $configure_closure)
    {
        $this->error_if_not_defined($name);
        $template = $this->templates[$name];
        $extant_closure = $template->template;

        $template->template = function ($object, $factory) use ($configure_closure, $extant_closure) {
            $extant_closure($object, $factory);
            $configure_closure($object, $factory);
        };
    }

    public function extend($name, $parent, $configure_closure)
    {
        $this->error_if_not_defined($parent);
        $this->error_if_already_defined($name);

        $template = new DB_Model_Factory_Template($name, $configure_closure, $this);
        $template->parent = $this->templates[$parent];

        $this->templates[$name] = $template;
    }

    // if attribute already exists, overwrite it
    public function overwrite_attribute($name, $configure_closure)
    {
        $this->attributes[$name] = $configure_closure;
    }
    public function attribute($name, $configure_closure)
    {
        $this->error_if_already_defined($name);
        $this->attributes[$name] = $configure_closure;
    }

    private function error_if_already_defined($name)
    {
        if (isset($this->templates[$name]) || isset($this->attributes[$name])) {
            throw new Exception("DB Model Factory definition or attribute '$name' has already been defined");
        }
    }

    private function error_if_not_defined($name)
    {
        if (!isset($this->templates[$name])) {
            throw new Exception("DB Model Factory parent definition '$name' doesn't exist");
        }
    }

    public function apply_attribute($object, $attribute_value)
    {
        if (is_a($attribute_value, 'Closure')) {
            $attribute_value($object, $this);

            return;
        } elseif (is_array($attribute_value)) {
            foreach ($attribute_value as $key => $attribute) {
                if (!is_numeric($key)) { // detect if this is a hash or an array
                    $setter = "set_$key";
                    $object->$setter($attribute);
                } else {
                    $this->apply_attribute($object, $attribute);
                }
            }

            return;
        }
        $attribute = $this->attributes[$attribute_value];
        if ($attribute) {
            $attribute($object, $this);
        } else {
            throw new Exception("DB Model Factory attribute '$attribute_value' does not exist");
        }
    }

    public function __call($name, $args)
    {
        if (isset($this->attributes[$name])) {
            $this->apply_attribute($args[0], $name);

            return;
        }

        $template = array_lookup($this->templates, $name);
        if (!$template) {
            throw new Exception("DB Model Factory definition or attribute '$name' does not exist");
        }
        $object = $template->create($this, $args);

        return $object;
    }

    /**
     * Returns the list of all defined template names in this factory
     * @return string[]
     */
    public function template_names()
    {
        return array_keys($this->templates);
    }

    public function db_model_class_for_template_name($template_name)
    {
        $template = $this->templates[$template_name];

        return $template->find_class();
    }
}

/**
 * Stores the internal state of individual factories, including definitions, extensions and attributes.
 * Delegates sequence management and closure parameters to DB_Model_Proxy instances
 */
class DB_Model_Factory_Template
{
    public $name = null;
    public $template = null;
    public $parent = null;
    public $class = null;

    /**
     * @param name string the name of the factory
     * @param template callable the configuration closure
     * @param factory DB_Model_Factory the parent factory
     */
    public function __construct($name, $template, $factory)
    {
        $this->name = $name;
        $this->template = $template;
    }

    public function find_class()
    {
        if ($this->class) {
            if (!class_exists($this->class)) {
                throw new Exception("DB Model Factory template {$this->name} is improperly configured. Class {$this->class} doesn't exist");
            }

            return $this->class;
        }
        if (!$this->parent) {
            throw new Exception("DB Model Factory template {$this->name} is improperly configured. Missing class definition");
        }
        $parent_class = $this->parent->find_class();

        return $parent_class;
    }

    public function create($factory, $attributes)
    {
        $class = $this->find_class();
        $instance = new $class();
        $proxy = new DB_Model_Instance_Proxy($instance, $factory);
        $this->configure($factory, $proxy);
        foreach ($attributes as $attribute) {
            $factory->apply_attribute($proxy, $attribute);
        }
        $proxy->save();

        return $instance;
    }

    public function configure($factory, $object)
    {
        if ($this->parent) {
            $this->parent->configure($factory, $object);
        }
        $fn = $this->template;
        $fn($object, $factory);
    }
}
