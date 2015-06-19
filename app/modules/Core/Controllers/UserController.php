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
use Core\Models\UserEmailConfirmations;
use Engine\Controller;
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
        try {
            $this->auth->login($form);
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
            $this->auth->redirectReturnUrl();
        }
        $this->view->form = $form;
    }

    /**
     * Login with Facebook account
     */
    public function loginWithFacebookAction()
    {
        try {
            $this->view->disable();
            $this->auth->loginWithFacebook();
        } catch(\Exception $e) {
            $this->flash->error($this->t->_('There was an error connecting to %name%', ['name' => 'Facebook']));
            $this->auth->redirectReturnUrl();
        }
    }

    /**
     * Login with Google account
     */
    public function loginWithGoogleAction()
    {
        try {
            $this->view->disable();
            $this->auth->loginWithGoogle();
        } catch(\Exception $e) {
            $this->flash->error($this->t->_('There was an error connecting to %name%', ['name' => 'Google']));
            $this->auth->redirectReturnUrl();
        }
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
                    $this->flash->error($this->t->_('Passwords don\'t match'));
                }
                $user = new User();
                $user->setUsername($username);
                $user->setPassword($this->security->hash($password));
                $user->setEmail($email);
                $user->setRoleId(1);
                $user->setStatus(0);
                $user->setResetToken($this->security->generateToken());
                $user->setCreatedAt(time());
                if (!$user->save()) {
                    $this->flashErrors($user);
                } else {
                    // send email
                    $form->clear();
                    $this->flash->success($this->t->_('Thanks for signing up. An email has been sent to activate your account.'));
                }
            } else {
                $this->flashErrors($form);
            }
        }
        $this->view->form = $form;
    }

    /**
     * Remove user session
     * @return \Phalcon\Http\Response
     */
    public function logoutAction()
    {
        $this->view->disable();
        $this->auth->remove();
        $this->flashSession->success($this->t->_('You have been logged out successfully'));
    }

    /**
     * Confirms an email
     */
    public function confirmEmailAction()
    {
        $code = $this->dispatcher->getParam('code', 'alphanum');
        $confirmation = UserEmailConfirmations::findFirstByToken(hash('sha256', $code));
        if (!$confirmation) {
            //
            $this->flash->error('confirmation failed or token expired');
            $this->dispatcher->forward([
                'controller' => 'index',
                'action' => 'index'
            ]);
        }
        $confirmation->user->status = 1;

        // Change the confirmation to 'confirmed' and update the user to 'active'
        if (!$confirmation->user->save()) {
            foreach ($confirmation->getMessages() as $message) {
                $this->flash->error($message);
            }
            $this->dispatcher->forward([
                'controller' => 'index',
                'action' => 'index'
            ]);
        }

        $this->flash->success('Your email was successfully confirmed');
        $this->dispatcher->forward([
            'controller' => 'user',
            'action' => 'login'
        ]);
    }

/*
    public function resetPasswordAction()
    {
        $code = $this->dispatcher->getParam('code');
        $resetPassword = ResetPasswords::findFirstByCode($code);
        if (!$resetPassword) {
            return $this->dispatcher->forward([
                'controller' => 'index',
                'action' => 'index'
            ]);
        }
        if ($resetPassword->reset != 'N') {
            return $this->dispatcher->forward([
                'controller' => 'session',
                'action' => 'login'
            ]);
        }
        $resetPassword->reset = 'Y';

        // Change the confirmation to 'reset'
        if (!$resetPassword->save()) {
            foreach ($resetPassword->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward([
                'controller' => 'index',
                'action' => 'index'
            ]);
        }

        // Identify the user in the application
        $this->auth->authUserById($resetPassword->usersId);
        $this->flash->success('Please reset your password');
        return $this->dispatcher->forward([
            'controller' => 'users',
            'action' => 'changePassword'
        ]);
    }
    */
}