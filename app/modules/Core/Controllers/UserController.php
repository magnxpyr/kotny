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

/**
 * Class UserController
 * @package Core\Controllers
 */
class UserController extends Controller {

    /**
     * Login user
     */
    public function loginAction() {
        $this->_setTitle('Login');
        $form = new LoginForm();
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
            //    $post = $this->request->getPost('username', 'alphanum');
            //    if ($form->isValid()) {
                    $username = $this->request->getPost('username', 'alphanum');
                    $password = $this->request->getPost('password');
                    $user = User::findFirst("username='$username'");
                    if($username) {
                        if ($this->security->checkHash($password, $user->password)) {
                            $this->registerSession($user);
                            $this->response->redirect('user/login');
                        } else {
                            $this->flash->error('Username or password is invalid');
                        }
                    }
            //    } else {
           //         $this->_flashErrors($form);
           //     }
            }
        }
        $this->view->form = $form;
    }

    /**
     * Register user
     */
    public function registerAction() {
        $this->_setTitle('Register');
        $form = new RegisterForm();
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username', 'alphanum');
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');
            if ($password != $repeatPassword) {
                $this->flash->error('Passwords don\'t match');
            }
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->security->hash($password));
            $user->setEmail($email);
            $user->setRole(1);
            $user->setStatus(0);
            if (!$user->save()) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $form->clear();
                $this->flash->success('Thanks for signing up, please log-in to manage your account');
            }
        }
        $this->view->form = $form;
    }

    /**
     * Register an authenticated user into session data
     * @param User $user
     */
    private function registerSession(User $user) {
        $this->session->set('auth', array(
            'id' => $user->getId(),
            'username' => $user->getUsername()
        ));
    }

    public function logoutAction() {
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $this->session->remove('auth');
                $this->response->redirect('');
                return $this->response->send();
            } else {
                die("Security errors");
            }
        } else {
            die("Security errors");
        }
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