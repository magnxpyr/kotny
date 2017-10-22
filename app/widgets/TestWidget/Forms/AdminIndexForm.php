<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\TestWidget\Forms;

use Engine\Forms\Form;
use Module\Core\Models\Category;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminIndexForm
 * @package Widget\TopContent\Forms
 */
class AdminIndexForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        // Category
        $category = new Select('widgetCategory',
            Category::find(),
            ['using' => ['id', 'title']]
        );
        $category->setLabel($this->t->_('Category'));
        $category->setFilters('int');
        $category->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Category')])
            ])
        );
        $this->add($category);

        // Limit
        $limit = new Numeric('widgetLimit');
        $limit->setLabel($this->t->_('Limit'));
        $limit->setFilters('int');
        $limit->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Limit')])
            ])
        );
        $this->add($limit);
    }
}
