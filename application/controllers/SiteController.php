<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 2:22
 */

namespace application\controllers;

use core\App;
use core\Controller;
use core\Exception;

/**
 * Class SiteController
 * @package application\controllers
 */
class SiteController extends Controller
{
    /**
     * Home
     * @throws \core\Exception
     */
    public function actionIndex()
    {
        if (App::$instance->user->isGuest()) {
            $this->redirect('site/login');
        }

        $user = App::$instance->user->model;
        $accountHistory = $user->getAccountHistory();
        $userBalance = $user->getBalance();

        $this->render('index', [
            'user'           => $user,
            'accountHistory' => $accountHistory,
            'userBalance'    => $userBalance,
        ]);
    }

    /**
     * Login
     * @throws \core\Exception
     */
    public function actionLogin()
    {
        if (!App::$instance->user->isGuest()) {
            $this->goHome();
        }

        $action = isset($_POST['action']);
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $error = '';
        if ($action) {
            App::$session->open(true);
            $auth = App::$instance->user->auth($username, $password);
            if ($auth) {
                $this->goHome();
            } else {
                App::$session->destroy();
                $error = 'Incorrect username or password';
            }
            App::$session->writeClose();
        }

        $this->render('login', [
            'error'    => $error,
            'username' => $username,
        ]);
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        App::$session->open(true);
        App::$session->destroy();
        $this->goHome();
    }

    /**
     * Pay
     * @throws \core\Exception
     */
    public function actionPay()
    {
        if (App::$instance->user->isGuest()) {
            $this->redirect('site/login');
        }

        $error = '';
        $pay = false;
        $action = isset($_POST['action']);
        $value = isset($_POST['money']) ? round($_POST['money'], 2) : 0;
        $user = App::$instance->user->model;

        if ($action) {
            if ($user->getBalance() >= $value) {
                $pay = $user->createPay($value);
                if (!$pay) {
                    $error = 'An error occurred during the transaction';
                }
            } else {
                $error = 'You do not have enough money';
            }
        } else {
            $this->goHome();
        }

        $this->render('pay', [
            'user'  => $user,
            'value' => $value,
            'error' => $error,
            'pay'   => $pay,
        ]);
    }

    /**
     * Confirm pay
     * @throws \core\Exception
     */
    public function actionConfirmPay()
    {
        if (App::$instance->user->isGuest()) {
            $this->redirect('site/login');
        }

        $error = '';
        $action = isset($_POST['action']);
        $payHash = isset($_POST['pay-hash']) ? $_POST['pay-hash'] : null;
        $user = App::$instance->user->model;

        if ($action) {
            $pay = $user->pay($payHash);
            if (!$pay) {
                throw new Exception('An error occurred during the transaction');
            } else {
                $this->redirect('site/successPay');
            }
        } else {
            $this->goHome();
        }
    }

    /**
     * Success pay page
     */
    public function actionSuccessPay()
    {
        $this->render('successPay');
    }
}