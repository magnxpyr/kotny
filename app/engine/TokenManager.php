<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

use Phalcon\Mvc\User\Component;

/**
 * Class TokenManager
 * @package Engine
 */
class TokenManager extends Component
{
    use Meta;
    
    /**
     * Generates token per session
     */
    public function generateToken()
    {
        $this->session->set('sessionToken', $this->security->getToken());
    }
    /**
     * Checks token given values against session values
     *
     * @param $tokenValue
     * @return bool
     */
    public function checkToken($tokenValue)
    {
        if ($this->session->has('sessionToken') && $this->session->get('sessionToken') == $tokenValue) {
            return true;
        }
        return false;
    }
    /**
     * Checks if user have token or not
     *
     * @return bool
     */
    public function doesUserHaveToken()
    {
        if ($this->session->has('sessionToken')) {
            return true;
        }
        return false;
    }
    /**
     * Gets token values from session
     *
     * @return array|bool
     */
    public function getToken()
    {
        if ($this->session->has('sessionToken')) {
            return $this->session->get('sessionToken');
        } else {
            return $this->generateToken();
        }
    }
}