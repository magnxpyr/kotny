<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Carousel\Forms;

use Engine\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminIndexForm
 * @package Widget\Carousel\Forms
 */
class AdminIndexForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        // Images
        $images = new Text('_images');
        $images->setLabel($this->t->_('Images'));
        $images->setFilters('string');
        $this->add($images);

        // Text
        $text = new TextArea('_text');
        $text->setLabel($this->t->_('Text'));
        $text->setFilters(['escapeHtml', 'string']);
        $this->add($text);
    }
}