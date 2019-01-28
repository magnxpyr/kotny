<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\TopContent\Forms;

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
        // Featured
        $featured = new Select('_featured', [0 => 'No', 1 => 'Yes'], ['class' => 'form-control']);
        $featured->setLabel($this->t->_('Featured'));
        $featured->setFilters('int');
        $this->add($featured);

        // Category
        $category = new Select('_category',
            Category::find(),
            [
                'using' => ['id', 'title'],
                'useEmpty' => true,
                'emptyText' => "All"
            ]
        );
        $category->setLabel($this->t->_('Category'));
        $category->setFilters('int');
        $this->add($category);

        // Limit
        $limit = new Numeric('_limit', ['required' => 'required']);
        $limit->setDefault(10);
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