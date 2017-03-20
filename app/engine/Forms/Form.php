<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

namespace Engine\Forms;
use Engine\Meta;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;

/**
 * Class Form
 * @package Engine\Forms
 */
class Form extends \Phalcon\Forms\Form
{
    use Meta;

    private $recaptcha;

    private $showRecaptcha;

    /**
     * @param bool $recaptcha
     * @param bool $showRecaptcha
     */
    public function setRecaptcha($recaptcha, $showRecaptcha = false)
    {
        $this->recaptcha = $recaptcha;
        $this->showRecaptcha = $showRecaptcha;

        if ($recaptcha) {
            $this->assets->collection('footer-js')->addJs("https://www.google.com/recaptcha/api.js", false);
        }
    }

    /**
     * Render reCaptcha form where is called
     */
    public function showRecaptcha()
    {
        echo "<div class=\"g-recaptcha\" data-sitekey=\"" . $this->config->api->google->recaptcha->siteKey . "\"></div>";
    }

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

    public function setValue($field, $value) {
        $this->_entity->$field = $value;
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

        if ($element->getAttribute("timestamp")) {
            $element->getForm()->setValue($name, date('Y-m-d', $element->getValue()));
        }

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

        $label = '';
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

        if ($element->getAttribute("placeholder")) {
            $input .= 'placeholder="' . $element->getAttribute("placeholder") . '"';
        }

        $html .= '<div '. $group. '>';
        $html .= '<label for="'. $element->getName(). '" '. $label. '>'. $element->getLabel(). '</label>';
        $html .= '<div ' . $input . '>' . $element . '</div>';
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

        if ($this->showRecaptcha) {
            $html .= "<div class=\"g-recaptcha\" data-sitekey=\"" . $this->config->api->google->recaptcha->siteKey . "\"></div>";
        }
        $html .= '</form>';
        return $html;
    }
}