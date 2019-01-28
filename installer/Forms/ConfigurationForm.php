<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Installer\Forms;

use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class LoginForm
 * @package Installer\Forms
 */
class ConfigurationForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        // Adaptor
        $adaptor = new Select('db-adapter',
            ['mysql' => 'Mysql'/*, 'postgresql' => 'PostgreSQL', 'oracle' => 'Oracle'*/]);
        $adaptor->setLabel("Database Type");
        $adaptor->setFilters('alphanum');
        $this->add($adaptor);

        // Hostname
        $hostname = new Text('db-hostname');
        $hostname->setLabel("Hostname");
        $hostname->setFilters('alphanum');
        $hostname->setDefault('localhost');
        $hostname->addValidators([
            new PresenceOf([
                'message' => 'Please enter your desired hostname'
            ])
        ]);
        $this->add($hostname);

        // Username
        $username = new Text('db-username');
        $username->setLabel('Username');
        $username->setFilters('alphanum');
        $username->addValidators([
            new PresenceOf([
                'message' => 'Please enter your database username'
            ])
        ]);
        $this->add($username);

        // Password
        $password = new Password('db-password');
        $password->setLabel('Password');
        $password->setFilters('string');
        $password->addValidators([
            new PresenceOf([
                'message' => 'Password is required'
            ])
        ]);
        $this->add($password);

        // Name
        $name = new Text('db-name');
        $name->setLabel('Database name');
        $name->setFilters('alphanum');
        $name->addValidators([
            new PresenceOf([
                'message' => 'Please enter your database username'
            ])
        ]);
        $this->add($name);

        // Port
        $port = new Numeric('db-port');
        $port->setLabel('Database Port');
        $port->setFilters('numeric');
        $port->setDefault(3306);
        $port->addValidators([
            new PresenceOf([
                'message' => 'Please enter your database port'
            ])
        ]);
        $this->add($port);

        // Prefix
        $prefix = new Text('db-prefix');
        $prefix->setLabel('Database Prefix');
        $prefix->setFilters('string');
        $this->add($prefix);

        // Email
        $email = new Email('su-email');
        $email->setLabel('Email');
        $email->setFilters('alphanum');
        $email->addValidators([
            new PresenceOf([
                'message' => 'Please enter your email address'
            ]),
            new EmailValidator([
                'message' => 'Email is not valid'
            ])
        ]);
        $this->add($email);


        // Username
        $username = new Text('su-username');
        $username->setLabel('Username');
        $username->setFilters('alphanum');
        $username->addValidators([
            new PresenceOf([
                'message' => 'Please enter your desired username'
            ]),
            new Alpha([
                'message' => 'Username is not valid'
            ])
        ]);
        $this->add($username);

        // Password
        $password = new Password('su-password');
        $password->setLabel('Password');
        $password->setFilters('string');
        $password->addValidators([
            new PresenceOf([
                'message' => 'Password is required'
            ])
        ]);
        $this->add($password);

        // Repeat Password
        $repeatPassword = new Password('su-password-repeat');
        $repeatPassword->setLabel('Repeat Password');
        $repeatPassword->setFilters('string');
        $repeatPassword->addValidators([
            new PresenceOf([
                'message' => 'Password is required'
            ]),
            new Identical([
                'accepted' => $password->getValue(),
                'message' => 'Password does not match'
            ])
        ]);
        $this->add($repeatPassword);
    }
}