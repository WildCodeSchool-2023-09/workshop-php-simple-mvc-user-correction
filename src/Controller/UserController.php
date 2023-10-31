<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function login(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $credentials = array_map('trim', $_POST);
//      @todo make some controls on email and password fields and if errors, send them to the view
            $userManager = new UserManager();
            $user = $userManager->selectOneByEmail($credentials['email']);
            if ($user && password_verify($credentials['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: /');
                exit();
            }
        }
        return $this->twig->render('User/login.html.twig', [
            'errors' => $errors
        ]);
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        header('Location: /');
    }

    public function register(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//      @todo make some controls and if errors send them to the view
            $credentials = $_POST;
            $userManager = new UserManager();
            if ($userManager->insert($credentials)) {
                return $this->login();
            }
        }
        return $this->twig->render('User/register.html.twig');
    }
}
