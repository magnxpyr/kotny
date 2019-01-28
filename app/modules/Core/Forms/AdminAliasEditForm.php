<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Engine\Forms\Element\Datalist;
use Engine\Package\PackageType;
use Module\Core\Models\Package;
use Module\Core\Models\Route;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminUserEditForm
 * @package Module\Core\Forms
 */
class AdminAliasEditForm extends Form
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

        // Url
        $url = new Text('url', [
            'class' => 'form-control'
        ]);
        $url->setLabel($this->t->_('Url'));
        $url->setFilters('url');
        $url->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Url')])
            ])
        ]);
        $this->add($url);

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
        $paramVal->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Params')])
            ])
        );
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

        // Route
        $route = new Datalist('route_id',
            Route::find([
                'conditions' => 'params is not null'
            ]),
            ['using' => ['name', 'id'], 'class' => 'form-control']
        );
        $route->setLabel($this->t->_('Route'));
        $route->setFilters('int');
        $route->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Route')])
            ])
        );
        $this->add($route);
    }

    public function bind(array $data, $entity, $whitelist = null)
    {
        if (isset($data['_paramKey']) && $data['_paramKey'] != null) {
            $params = [];
            foreach ($data['_paramKey'] as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $params[$value] = $data['_paramValue'][$key];
                }
            }
            if (!empty($params)) {
                $entity->setParams(json_encode($params));
            }
        }

        $data['url'] = empty($data['url']) ? null : trim($data['url'], '/');

        parent::bind($data, $entity, $whitelist);

        if (empty($data['route_id'])) {
            $entity->setRouteId(null);
        }
    }

    public function setEntity($entity)
    {
        $entity->setParams($entity->getParamsArray());
        parent::setEntity($entity);
    }
}