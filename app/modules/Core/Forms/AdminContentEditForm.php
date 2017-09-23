<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Module\Core\Models\Category;
use Module\Core\Models\ViewLevel;
use Engine\Forms\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminContentEditForm
 * @package Module\Core\Forms
 */
class AdminContentEditForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        parent::initialize();
        
        // Id
        $id = new Hidden('id');
        $id->setFilters('int');
        $this->add($id);

        // Title
        $title = new Text('title', [
            'class' => 'form-control'
        ]);
        $title->setLabel($this->t->_('Title'));
        $title->setFilters('string');
        $title->addValidator(
            new PresenceOf([
                'title' => $this->t->_('%field% is required', ['field' => $this->t->_('Title')])
            ])
        );
        $this->add($title);

        // Alias
        $alias = new Text('alias', [
            'class' => 'form-control'
        ]);
        $alias->setLabel($this->t->_('Alias'));
        $alias->setAttribute('placeholder', $this->t->_("Generated from title"));
        $alias->setFilters('string');
        $this->add($alias);

        // Intro Text
        $introText = new TextArea('introtext', [
            'class' => 'form-control'
        ]);
        $introText->setLabel($this->t->_('Intro Text'));
        $introText->setFilters(['escapeHtml', 'string']);
        $this->add($introText);

        // Full Text
        $fullText = new TextArea('fulltext', [
            'class' => 'form-control'
        ]);
        $fullText->setLabel($this->t->_('Full Text'));
        $fullText->setFilters(['escapeHtml', 'string']);
        $this->add($fullText);

        // Metadata
        $metadata = new Text('metadata', [
            'class' => 'form-control'
        ]);
        $metadata->setLabel($this->t->_('Metadata'));
        $metadata->setFilters('string');
        $this->add($metadata);

        // Categories
        $category = new Select('category',
            Category::find(),
            ['using' => ['id', 'title'], 'class' => 'form-control']
        );
        $category->setLabel($this->t->_('Category'));
        $category->setFilters('int');
        $category->addValidator(
            new PresenceOf([
                'view_level' => $this->t->_('%field% is required', ['field' => $this->t->_('Category')])
            ])
        );
        $this->add($category);

        // Featured
        $featured = new Check('featured');
        $featured->setLabel($this->t->_('Featured'));
        $featured->setFilters('int');
        $this->add($featured);

        // Status
        $status = new Select('status',
            $this->helper->getArticleStatuses(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $status->setLabel($this->t->_('Status'));
        $status->setFilters('int');
        $status->addValidator(
            new PresenceOf([
                'status' => $this->t->_('%field% is required', ['field' => $this->t->_('Status')])
            ])
        );
        $this->add($status);

        // User view level
        $role = new Select('view_level',
            ViewLevel::find(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $role->setLabel($this->t->_('View Level'));
        $role->setFilters('int');
        $role->addValidator(
            new PresenceOf([
                'view_level' => $this->t->_('%field% is required', ['field' => $this->t->_('View Level')])
            ])
        );
        $this->add($role);

        $publishUp = new Text('publish_up', [
            'class' => 'form-control'
        ]);
        $publishUp->setLabel($this->t->_('Publish date'));
        $publishUp->setFilters('string');
        $publishUp->setAttribute('timestamp', true);
        $publishUp->addValidator(
            new PresenceOf([
                'title' => $this->t->_('%field% is required', ['field' => $this->t->_('Publish date')])
            ])
        );
        $this->add($publishUp);

        $publishDown = new Text('publish_down', [
            'class' => 'form-control'
        ]);
        $publishDown->setLabel($this->t->_('End date'));
        $publishDown->setFilters('string');
        $publishDown->setAttribute('timestamp', true);
        $this->add($publishDown);
    }
}