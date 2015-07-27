<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

namespace Engine\Forms;

/**
 * Class Form
 * @package Engine\Forms
 */
class Form extends \Phalcon\Forms\Form
{
    /**
     * Render label and input
     * @param \Phalcon\Forms\ElementInterface $name
     * @param array $attributes
     */
    public function renderDecorated($name, $attributes = [])
    {
        $element  = $this->get($name);

        // Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());

        if (count($messages)) {
            // Print each element
            echo '<div class="messages">';
            foreach ($messages as $message) {
                echo $this->flash->error($message);
            }
            echo '</div>';
        }

        $group = '';
        if (isset($attributes['group'])) {
            if (isset($attributes['group']['class'])) {
                $attributes['group']['class'] .= ' form-group';
            }
            foreach ($attributes['group'] as $key => $value) {
                $group .= "$key=\"$value\"";
            }
        }
        $label = isset($attributes['label']) ? $attributes['label'] : '';
        $input = isset($attributes['input']) ? $attributes['input'] .'"' : '';

        echo '<div class="form-group"', $group, '>';
        echo '<label for="', $element->getName(), '" ', $label, '>', $element->getLabel(), '</label>';
        echo '<div class="input-group" ', $input, '>', $element, '</div>';
        echo '</div>';
    }

    /**
     * @param string $action
     * @param array $attributes
     */
    public function renderForm($action, $attributes = [])
    {
        $formId = isset($attributes['formId']) ? 'id="' . $attributes['formId'] .'"' : '';
        $formClass = isset($attributes['formClass']) ? 'class="' . $attributes['formClass'] .'"' : '';

        echo '<form action="', $action, '" method="post" ', $formId, $formClass, '>';

        // Traverse the form
        foreach ($this->getElements() as $element) {
            // render the element
            $this->renderDecorated($element, $attributes);
        }

        echo '</form>';
    }
}