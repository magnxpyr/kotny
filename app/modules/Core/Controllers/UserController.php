<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\LoginForm;
use Core\Forms\RegisterForm;
use Core\Models\User;
use Engine\Controller;
use Engine\Plugins\Auth;
use Engine\Utils;

/**
 * Class UserController
 * @package Core\Controllers
 */
class UserController extends Controller
{
    /**
     * Login user
     */
    public function loginAction()
    {
        $this->setTitle('Login');
        $form = new LoginForm();
        if ($this->request->isPost()) {
            try {
                $this->auth->login($form);
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }
        $this->view->form = $form;
    }

    /**
     * Register user account
     */
    public function registerAction()
    {
        $this->setTitle('Register');
        $form = new RegisterForm();
        if ($this->request->isPost()) {
            if($form->isValid($this->request->getPost())) {
                $username = $this->request->getPost('username', 'alphanum');
                $email = $this->request->getPost('email', 'email');
                $password = $this->request->getPost('password');
                $repeatPassword = $this->request->getPost('repeatPassword');
                if ($password != $repeatPassword) {
                    $this->flash->error($this->t['Passwords don\'t match']);
                }
                $user = new User();
                $user->setUsername($username);
                $user->setPassword($this->security->hash($password));
                $user->setEmail($email);
                $user->setRole(1);
                $user->setStatus(0);
                $user->setResetToken(Utils::generateToken());
                if (!$user->save()) {
                    $this->flashErrors($user);
                } else {
                    // send email
                    $form->clear();
                    $this->flash->success($this->t['Thanks for signing up. An email has been sent to activate your account.']);
                }
            } else {
                $this->flashErrors($form);
            }
        }
        $this->view->form = $form;
    }

    /**
     * Remove user session
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->flash->success($this->t['You have been logged out successfully']);
        return $this->response->send();
    }

    /**
     * Confirms an e-mail, if the user must change their password then changes it
     */
    /*
    public function confirmEmailAction()
    {
        $code = $this->dispatcher->getParam('code');
        $confirmation = EmailConfirmations::findFirstByCode($code);
        if (!$confirmation) {
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }
        if ($confirmation->confirmed != 'N') {
            return $this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login'
            ));
        }
        $confirmation->confirmed = 'Y';
        $confirmation->user->active = 'Y';

        // Change the confirmation to 'confirmed' and update the user to 'active'
        if (!$confirmation->save()) {
            foreach ($confirmation->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        // Identify the user in the application
        $this->auth->authUserById($confirmation->user->id);

        // Check if the user must change his/her password
        if ($confirmation->user->mustChangePassword == 'Y') {
            $this->flash->success('The email was successfully confirmed. Now you must change your password');
            return $this->dispatcher->forward(array(
                'controller' => 'users',
                'action' => 'changePassword'
            ));
        }
        $this->flash->success('The email was successfully confirmed');
        return $this->dispatcher->forward(array(
            'controller' => 'users',
            'action' => 'index'
        ));
    }

    public function resetPasswordAction()
    {
        $code = $this->dispatcher->getParam('code');
        $resetPassword = ResetPasswords::findFirstByCode($code);
        if (!$resetPassword) {
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }
        if ($resetPassword->reset != 'N') {
            return $this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login'
            ));
        }
        $resetPassword->reset = 'Y';

        // Change the confirmation to 'reset'
        if (!$resetPassword->save()) {
            foreach ($resetPassword->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        // Identify the user in the application
        $this->auth->authUserById($resetPassword->usersId);
        $this->flash->success('Please reset your password');
        return $this->dispatcher->forward(array(
            'controller' => 'users',
            'action' => 'changePassword'
        ));
    }
    */
}