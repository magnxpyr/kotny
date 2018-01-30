<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Engine\Forms\Form;
use Engine\Package\PackageType;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminModuleEditForm
 * @package Module\Core\Forms
 */
class AdminPackageEditForm extends Form
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

        // Name
        $name = new Text('name', [
            'class' => 'form-control',
            'readonly' => 'readonly'
        ]);
        $name->setLabel($this->t->_('Name'));
        $name->setFilters('string');
        $name->addValidator(
            new PresenceOf([
                'title' => $this->t->_('%field% is required', ['field' => $this->t->_('Name')])
            ])
        );
        $this->add($name);

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

        // Type
        $type = new Select('type',
            PackageType::getConstants(),
            ['class' => 'form-control', 'readonly' => 'readonly']
        );
        $type->setLabel($this->t->_('Type'));
        $type->setFilters('string');
        $this->add($type);

        // Version
        $version = new Text('version', [
            'class' => 'form-control',
            'readonly' => 'readonly'
        ]);
        $version->setLabel($this->t->_('Version'));
        $version->setFilters('string');
        $this->add($version);

        // Author
        $author = new Text('author', [
            'class' => 'form-control',
            'readonly' => 'readonly'
        ]);
        $author->setLabel($this->t->_('Author'));
        $author->setFilters('string');
        $this->add($author);

        // Website
        $website = new Text('website', [
            'class' => 'form-control',
            'readonly' => 'readonly'
        ]);
        $website->setLabel($this->t->_('Website'));
        $website->setFilters('string');
        $this->add($website);

        // Description
        $description = new TextArea('description', [
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control',
            'readonly' => 'readonly'
        ]);
        $description->setLabel($this->t->_('Description'));
        $description->setFilters('string');
        $this->add($description);
    }
}