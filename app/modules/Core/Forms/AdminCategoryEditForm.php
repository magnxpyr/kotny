<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Module\Core\Models\ViewLevel;
use Engine\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminCategoryEditForm
 * @package Module\Core\Forms
 */
class AdminCategoryEditForm extends Form
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
        $alias->setFilters('string');
        $alias->setAttribute('placeholder', $this->t->_("Generated from title"));
        $this->add($alias);

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

        // Description
        $description = new TextArea('description', [
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control'
        ]);
        $description->setLabel($this->t->_('Description'));
        $description->setFilters('string');
        $this->add($description);

        // ---- SEO ----
        // Meta Title
        $metaTitle = new Text('metaTitle', [
            'class' => 'form-control'
        ]);
        $metaTitle->setLabel($this->t->_('Meta Title'));
        $metaTitle->setFilters('string');
        $this->add($metaTitle);

        // Meta Keywords
        $metaKeyword = new Text('metaKeywords', [
            'class' => 'form-control'
        ]);
        $metaKeyword->setLabel($this->t->_('Meta Keywords'));
        $metaKeyword->setFilters('string');
        $this->add($metaKeyword);

        // Meta Description
        $metaDescription = new Text('metaDescription', [
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control'
        ]);
        $metaDescription->setLabel($this->t->_('Meta Description'));
        $metaDescription->setFilters('string');
        $this->add($metaDescription);
    }

    public function bind(array $data, $entity, $whitelist = null)
    {
        $meta = [];
        foreach ($data as $key => $val) {
            if (substr( $key, 0, 4 ) === "meta") {
                $meta[$key] = $val;
                unset($data[$key]);
            }
        }
        $entity->setMetadata(json_encode($meta));
        parent::bind($data, $entity, $whitelist);
    }

    public function setEntity($entity)
    {
        $meta = $entity->getMetadataArray();
        if ($meta != null) {
            foreach ($meta as $key => $val) {
                if ($this->has($key)) {
                    $this->get($key)->setDefault($val);
                }
            }
        }
        parent::setEntity($entity);
    }
}