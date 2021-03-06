<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Forms\Element;

use Phalcon\Forms\Element;
use Phalcon\Tag\Exception;

/**
 * Class Datalist
 * @package Engine\Forms\Element
 */
class Datalist extends Element
{
    protected $_optionsValues;

    public function __construct($name, $options = null, $attributes = null)
    {
        $this->_optionsValues = $options;
        parent::__construct($name, $attributes);
    }

    /**
     * Set the choice's options
     *
     * @param array|object $options
     * @return \Phalcon\Forms\Element
     */
    public function setOptions($options) {
        $this->_optionsValues = $options;
        return $this;
    }

    /**
     * Returns the choices' options
     *
     * @return array|object
     */
    public function getOptions() {
        return $this->_optionsValues;
    }

    /**
     * Renders the datalist element widget
     * [0]: Contains input attributes.
     * [1]: Contains data (database or array)
     * [2]: Contains field name mappings for datalist options (key, value)
     *
     * @param array $attributes
     * @return string
     * @throws Exception
     */
    public function render($attributes = null)
    {
        $attributes = $this->getAttributes();
        if (is_object($this->getOptions())) {
            if (!isset($attributes['using'])) {
                throw new Exception("The 'using' parameter is required");
            }
            $fields = $attributes['using'];
            unset($attributes['using']);
        }

        $inputValue = "";
        $datalist = "<datalist  id='datalist_" . $this->getName() . "'>";
        if (is_object($this->getOptions())) {
            foreach ($this->getOptions() as $option) {
                $option = $option->toArray();
                $datalist .= "<option value='" . $option[$fields[0]] . "' data-value='" . $option[$fields[1]] . "' ></option>";
                if ($this->getValue() != null && $option[$fields[1]] == $this->getValue()) {
                    $inputValue = $option[$fields[0]];
                }
            }
        } elseif (is_array($this->getOptions())) {
            foreach ($this->getOptions() as $key => $value) {
                $datalist .= "<option value='$key' data-value='$value' ></option>";
                if ($this->getValue() != null && $value == $this->getValue()) {
                    $inputValue = $value;
                }
            }
        }
        $datalist .="</datalist>";

        $input = "<input type='text' id='" . $this->getName() . "' name='" . $this->getName() .
            "' value='" . $inputValue . "' list='datalist_" . $this->getName(). "' ";
        foreach($attributes as $key => $value) {
            $input .= " $key='$value' ";
        }
        $input .= ">";

        return $input . $datalist;
    }
}