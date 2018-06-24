<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Forms\Element;

use Engine\Di\DiTrait;
use Phalcon\Forms\Element;
use Phalcon\Tag\Exception;

/**
 * Class Datalist
 * @package Engine\Forms\Element
 */
class MultiSelect extends Element
{
    use DiTrait;

    protected $_optionsValues;

    public function __construct($name, $options = null, $attributes = null)
    {
        $this->getDI()->getShared('assets')->collection('footer-js')->addJs("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js");
        $this->getDI()->getShared('assets')->collection('header-css')->addCss("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css");
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

        $html = "<select name='" . $this->getName() . "[]' " .
            "id='" . $this->getName() . "' class='multiselect form-control' multiple='multiple' ";
        foreach($attributes as $key => $value) {
            $html .= " $key='$value' ";
        }
        $html .= ">";

        if (is_object($this->getOptions())) {
            foreach ($this->getOptions() as $option) {
                $option = $option->toArray();
                $selected = $this->getValue() != null && in_array($option[$fields[1]], $this->getValue()) ? "selected" : "";
                $html .= "<option value='" . $option[$fields[0]] . "' $selected>" . $option[$fields[1]] . "</option>";
            }
        } elseif (is_array($this->getOptions())) {
            foreach ($this->getOptions() as $key => $value) {
                $selected = $this->getValue() != null && in_array($value, $this->getValue()) ? "selected" : "";
                $html .= "<option value='$key' $selected>$value</option>";
            }
        }
        $html .="</select>";

        return $html;
    }
}