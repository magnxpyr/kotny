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
     *
     * @param \Phalcon\Forms\ElementInterface $name
     * @param array $attributes
     * @return string
     */
    public function renderDecorated($name, $attributes = [])
    {
        $element  = $this->get($name);

        // Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());
        $html = '';
        if (count($messages)) {
            // Print each element
            $html .= '<div class="messages">';
            foreach ($messages as $message) {
                $html .= $this->flash->error($message);
            }
            $html .= '</div>';
        }

        $group = '';
        if (isset($attributes['group'])) {
            if (isset($attributes['group']['class'])) {
                $attributes['group']['class'] .= ' form-group ';
            } else {
                $group .= 'class="form-group" ';
            }
            foreach ($attributes['group'] as $key => $value) {
                $group .= "$key=\"$value\"";
            }
        } else {
            $group .= 'class="form-group" ';
        }

        $label ='';
        if (isset($attributes['label'])) {
            foreach ($attributes['label'] as $key => $value) {
                $label .= "$key=\"$value\"";
            }
        }

        $input = '';
        if (isset($attributes['input'])) {
            if (isset($attributes['input']['class'])) {
                $attributes['input']['class'] .= ' input-group';
            } else {
                $input .= 'class="input-group" ';
            }
            foreach ($attributes['input'] as $key => $value) {
                $input .= "$key=\"$value\" ";
            }
        } else {
            $input .= 'class="input-group" ';
        }

        $html .= '<div '. $group. '>';
        $html .= '<label for="'. $element->getName(). '" '. $label. '>'. $element->getLabel(). '</label>';
        $html .= '<div '. $input. '>'. $element. '</div>';
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