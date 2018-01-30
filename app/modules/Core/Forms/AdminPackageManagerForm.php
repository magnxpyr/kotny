<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Engine\Forms\Form;
use Phalcon\Forms\Element\File as FileElement;
use Phalcon\Validation\Validator\File as FileValidator;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminPackageManagerForm
 * @package Module\Core\Forms
 */
class AdminPackageManagerForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        parent::initialize();

        $browse = new FileElement('packageUpload', ['class' => 'form-control']);
        $browse->setLabel($this->t->_('Upload package'));
        $browse->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Upload package')])
            ]),
            new FileValidator([
                'allowedTypes' => [
                    'application/x-compressed',
                    'application/x-zip-compressed',
                    'application/zip',
                ],
                'messageType' => $this->t->_('The package must be zip archive'),
            ])
        ]);
        $this->add($browse);
    }
}