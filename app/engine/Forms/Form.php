<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

namespace Engine\Forms;

use Engine\Meta;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Form
 * @package Engine\Forms
 */
class Form extends \Phalcon\Forms\Form
{
    use Meta;

    /**
     * Returns the default value for field 'csrf'
     */
    public function getCsrf()
    {
        return $this->tokenManager->getToken();
    }
    
    public function initialize()
    {
        if (!$this->tokenManager->doesUserHaveToken()) {
            $this->tokenManager->generateToken();
        }

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
            'value' => $this->tokenManager->getToken(),
            'message' => $this->t->_('CSRF validation failed')
        ]));
        $csrf->clear();
        $this->add($csrf);
    }

    public function setValue($field, $value = null) {
        if ($this->_entity != null) {
            $this->_entity->$field = $value;
        }
        if ($this->_data != null) {
            $this->_data[$field] = $value;
        }
    }

    public function mapEntity($data)
    {
        if ($data != null) {
            foreach ($data as $key => $val) {
                if ($this->has($key)) {
                    $this->get($key)->setDefault($val);
                }
            }
        }
    }

    public function getEntityValue($name)
    {
        if ($this->has($name)) {
            return $this->get($name)->getValue();
        }
        return null;
    }

    /**
     * Renders a specific item in the form if the item exists
     *
     *
     * @param string $name
     * @param array $attributes
     * @return string
     */
    public function render($name, $attributes = null)
    {
        if ($this->has($name)) {
            $element = $this->get($name);
            foreach ($element->getValidators() as $validator) {
                if ($validator instanceof PresenceOf) {
                    $attributes['required'] = 'required';
                    break;
                }
            }

            if ($element->getAttribute("timestamp") && $element instanceof Text && $element->getValue() != null) {
                $element->getForm()->setValue($name, $this->helper->dateFromTimestamp($element->getValue()));
            }

            return parent::render($name, $attributes);
        }
    }


    /**
     * Render label and input
     *
     * @param \Phalcon\Forms\ElementInterface $name
     * @param array $attributes
     * @return string
     */
    public function renderDecorated($name, $attributes = [])
    {
        $element = $this->get($name);

        $inputDate = false;
        if ($element->getAttribute("timestamp")) {
            if ($element instanceof Text) {
                $inputDate = true;
            }
            if ($element->getValue() != null) {
                $element->getForm()->setValue($name, $this->helper->dateFromTimestamp($element->getValue()));
            }
        }

        // Get any generated messages for the current element
//        $messages = $this->getMessagesFor($element->getName());
        $html = '';
//        if (count($messages)) {
//            // Print each element
//            $html .= '<div class="messages">';
//            foreach ($messages as $message) {
//                $html .= $this->flash->error($message);
//            }
//            $html .= '</div>';
//        }

        $group = '';
        if (isset($attributes['group'])) {
            if (isset($attributes['group']['class'])) {
                $attributes['group']['class'] .= ' form-group ';
            } else {
                $attributes['group']['class'] = 'form-group ';
            }
            foreach ($attributes['group'] as $key => $value) {
                $group .= "$key=\"$value\"";
            }
        } else {
            $group .= 'class="form-group" ';
        }

        $label = '';
        if (isset($attributes['label'])) {
            foreach ($attributes['label'] as $key => $value) {
                $label .= "$key=\"$value\"";
            }
        }

        $input = '';
        foreach ($element->getValidators() as $validator) {
            if ($validator instanceof PresenceOf) {
                $element->setAttribute('required', 'required');
                break;
            }
        }

        if (isset($attributes['input'])) {
            if (isset($attributes['input']['class'])) {
                $attributes['input']['class'] .= ' input-group';
                if ($inputDate) {
                    $input .= ' date';
                }
            } else {
                $input .= 'class="input-group';
                if ($inputDate) {
                    $input .= ' date';
                }
                $input .= '" ';
            }
            foreach ($attributes['input'] as $key => $value) {
                $input .= "$key=\"$value\" ";
            }
        } else {
            $input .= 'class="input-group';
            if ($inputDate) {
                $input .= ' date';
            }
            $input .= '" ';
        }
        $input .= ' id="wrapper-' . $element->getName() . '" ';

        if ($element->getAttribute("placeholder")) {
            $input .= 'placeholder="' . $element->getAttribute("placeholder") . '"';
        }

        $html .= '<div '. $group. '>';
        $html .= '<label for="'. $element->getName(). '" '. $label. '>'. $element->getLabel(). '</label>';
        $html .= '<div ' . $input . '>' . $element;
        if ($inputDate) {
            $html .= '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * @param string $action
     * @param array $attributes
     * @return string
     */
    public function renderForm($action, $attributes = [])
    {
        $form = '';
        if (isset($attributes['form'])) {
            if (!isset($attributes['form']['method'])) {
                $form .= 'method="post" ';
            }
            foreach ($attributes['form'] as $key => $value) {
                $form .= "$key=\"$value\" ";
            }
        }

        $html = '<form action="'. $action. '" '. $form. '>';

        // Traverse the form
        foreach ($this->getElements() as $element) {
            $elemName = $element->getName();
            $fieldAttribute = $attributes;
            if (isset($attributes['field'][$elemName])) {
                $fieldAttribute = array_merge($fieldAttribute, $attributes['field'][$elemName]);
            }

            // render the element
            $html .= $this->renderDecorated($elemName, $fieldAttribute);
        }
        
        $html .= '</form>';
        return $html;
    }
}