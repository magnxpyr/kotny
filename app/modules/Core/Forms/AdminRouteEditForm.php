<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Engine\Forms\Element\Datalist;
use Engine\Forms\Element\MultiSelect;
use Engine\Package\PackageType;
use Module\Core\Models\Package;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminUserEditForm
 * @package Module\Core\Forms
 */
class AdminRouteEditForm extends Form
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
            'class' => 'form-control'
        ]);
        $name->setLabel($this->t->_('Name'));
        $name->setFilters('string');
        $name->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Name')])
            ])
        ]);
        $this->add($name);

        // Pattern
        $pattern = new Text('pattern', [
            'class' => 'form-control'
        ]);
        $pattern->setLabel($this->t->_('Pattern'));
        $pattern->setFilters('url');
        $pattern->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Pattern')])
            ])
        ]);
        $this->add($pattern);

        // Method
        $method = new MultiSelect('method',
            ["GET" => "GET", "POST" => "POST", "PUT" => "PUT", "DELETE" => "DELETE",
                "HEAD" => "HEAD", "OPTIONS" => "OPTIONS", "TRACE" => "TRACE", "CONNECT" => "CONNECT"],
            ['class' => 'form-control']
        );
        $method->setLabel($this->t->_('Method'));
        $method->setFilters('striptags');
        $this->add($method);

        // Package
        $package = new Datalist('package_id',
            Package::find([
                'conditions' => 'type = ?1',
                'bind' => [1 => PackageType::module],
                'columns' => ['id', 'name']
            ]),
            ['using' => ['name', 'id'], 'class' => 'form-control']
        );
        $package->setLabel($this->t->_('Package'));
        $package->setFilters('int');
        $package->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Package')])
            ])
        );
        $this->add($package);

        // Controller
        $controller = new Text('controller', [
            'class' => 'form-control'
        ]);
        $controller->setLabel($this->t->_('Controller'));
        $controller->setFilters('string');
        $controller->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Controller')])
            ])
        ]);
        $this->add($controller);

        // Action
        $action = new Text('action', [
            'class' => 'form-control'
        ]);
        $action->setLabel($this->t->_('Action'));
        $action->setFilters('string');
        $action->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Action')])
            ])
        ]);
        $this->add($action);

        // Params
        $params = new Text('params', [
            'class' => 'form-control'
        ]);
        $params->setLabel($this->t->_('Params'));
        $params->setFilters('string');
        $this->add($params);

        // Param Keys
        $paramKey = new Text('_paramKey', [
            'class' => 'form-control'
        ]);
        $paramKey->setLabel($this->t->_('Param Key'));
        $paramKey->setFilters('string');
        $this->add($paramKey);

        // Param Values
        $paramVal = new Text('_paramValue', [
            'class' => 'form-control'
        ]);
        $paramVal->setLabel($this->t->_('Param Value'));
        $paramVal->setFilters('string');
        $this->add($paramVal);

        // Status
        $status = new Select('status',
            $this->helper->getArticleStatuses(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $status->setLabel($this->t->_('Status'));
        $status->setFilters('int');
        $status->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Status')])
            ])
        );
        $this->add($status);
    }

    public function bind(array $data, $entity, $whitelist = null)
    {
        if ($data['_paramKey'] != null) {
            $params = [];
            foreach ($data['_paramKey'] as $key => $value) {
                $params[$value] = $data['_paramValue'][$key];
            }
            $entity->setParams(json_encode($params));
        }
        if ($data['method'] != null) {
            $data['method'] = json_encode($data['method']);
        }
        parent::bind($data, $entity, $whitelist);
    }

    public function setEntity($entity)
    {
        $entity->setMethod($entity->getMethodArray());
        $entity->setParams($entity->getParamsArray());
        parent::setEntity($entity);
    }
}