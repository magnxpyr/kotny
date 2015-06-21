<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\ConfirmEmailForm;
use Core\Forms\ForgotPasswordForm;
use Core\Forms\LoginForm;
use Core\Forms\RegisterForm;
use Core\Forms\ResetPasswordForm;
use Core\Models\User;
use Core\Models\UserEmailConfirmations;
use Core\Models\UserResetPasswords;
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
                $password = $this->request->getPost('password', 'string');
                $repeatPassword = $this->request->getPost('repeatPassword', 'string');
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
        $this->view->status = 1;

        $form = new ConfirmEmailForm();
        $this->view->form = $form;

        // If Post, user exist and not active, generate a new token
        if ($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $user = User::findFirstByEmail($this->request->get('email', 'email'))->load('userEmailConfirmations');
                $form->clear();
                if($user) {
                    if($user->getStatus() > 0) {
                        $this->flash->notice('Your account is already active');
                        $this->dispatcher->forward([
                            'controller' => 'user',
                            'action' => 'login'
                        ]);
                    }
                    if (!$user->userEmailConfirmations) {
                        $confirmation = new UserEmailConfirmations();
                        $confirmation->setUserId($user->getId());
                        $confirmation->save();
                    } else {
                        $user->userEmailConfirmations->setExpires(1);
                        $user->userEmailConfirmations->save();
                    }
                }
                $this->flash->success('Email sent. Please follow the instructions to activate your account');
                $this->dispatcher->forward([
                    'controller' => 'index',
                    'action' => 'index'
                ]);
            }
            return;
        }

        // if no code on url, we show the form
        $code = $this->request->get('code', 'alphanum');
        if(!$code) {
            $this->setTitle('Request email confirmation');
            return;
        }

        $confirmation = UserEmailConfirmations::findFirstByToken(hash('sha256', $code));
        if (!$confirmation) {
            $this->setTitle('Email confirmation failed');
            $this->view->status = 0;
            return;
        }

        // if token expired, error
        if($confirmation->getExpires() < time()) {
            $confirmation->delete();
            $this->setTitle('Email confirmation failed');
            $this->view->status = 0;
            return;
        }

        // Change the confirmation to 'confirmed' and update the user to 'active'
        $confirmation->user->status = 1;

        if (!$confirmation->user->save()) {
            foreach ($confirmation->getMessages() as $message) {
                $this->flash->error($message);
            }
            $this->setTitle('Email confirmation failed');
            $this->view->status = 0;
            return;
        }

        // delete the token
        $confirmation->delete();

        $this->flash->success('Your email was successfully confirmed. Please login to manage your account');
        $this->dispatcher->forward([
            'controller' => 'user',
            'action' => 'login'
        ]);
    }

    /**
     * Reset password
     */
    public function forgotPasswordAction()
    {
        $this->view->status = 1;
        $this->setTitle('Forgot password');

        $code = $this->request->get('code');

        if ($this->request->isPost()) {
            if (!$code) {
                $form = new ForgotPasswordForm();
                if ($form->isValid($_POST)) {
                    $user = User::findFirstByEmail($this->request->get('email', 'email'))->load('userResetPasswords');
                    $form->clear();
                    if ($user) {
                        if (!$user->userResetPasswords) {
                            $reset = new UserResetPasswords();
                            $reset->setUserId($user->getId());
                            $reset->save();
                        } else {
                            $user->userResetPasswords->setExpires(1);
                            $user->userResetPasswords->save();
                        }
                    }
                    $this->flash->success('Email sent. Please follow the instructions to change your password');
                    $this->dispatcher->forward([
                        'controller' => 'index',
                        'action' => 'index'
                    ]);
                }
            } else {
                $form = new ResetPasswordForm();
                if ($form->isValid($_POST)) {
                    $resetPassword = UserResetPasswords::findFirstByToken(hash('sha256', $code))->load('user');
                    if (!$resetPassword) {
                        $this->view->status = 0;
                        return;
                    }
                    if ($resetPassword->getExpires() < time()) {
                        $this->view->status = 0;
                        $resetPassword->delete();
                        return;
                    }

                    $password = $this->request->getPost('password', 'string');
                    $repeatPassword = $this->request->getPost('repeatPassword', 'string');
                    if ($password != $repeatPassword) {
                        $this->flash->error($this->t->_('Passwords don\'t match'));
                        return;
                    }
                    $resetPassword->user->setPassword($this->security->hash($password));

                    // Set a new password
                    if (!$resetPassword->user->save()) {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                        $this->dispatcher->forward([
                            'controller' => 'index',
                            'action' => 'index'
                        ]);
                    }

                    // Identify the user in the application
                    $this->auth->authUserById(null, $resetPassword->user);
                    $this->flash->success($this->t->_('Password changed successfully'));

                    $this->dispatcher->forward([
                        'controller' => 'index',
                        'action' => 'index'
                    ]);
                }
            }
            return;
        }

        $form = new ForgotPasswordForm();
        $this->view->form = $form;

        if(!$code) {
            return;
        } else {
            $resetPassword = UserResetPasswords::findFirstByToken(hash('sha256', $code));
            if (!$resetPassword) {
                $this->view->status = 0;
                return;
            }
            if ($resetPassword->getExpires() < time()) {
                $this->view->status = 0;
                $resetPassword->delete();
                return;
            }

            $this->view->status = 5;
            $this->setTitle('Reset password');
            $form = new ResetPasswordForm();
            $this->view->form = $form;
        }
    }
}