<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Carousel\Forms;

use Engine\Forms\Form;
use Phalcon\Forms\Element\Text;
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
        $category = new Text('_images');
        $category->setLabel($this->t->_('Images'));
        $category->setFilters('string');
        $category->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Images')])
            ])
        );
        $this->add($category);
    }
}