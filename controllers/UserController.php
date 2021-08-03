<?php

class UserController extends Controller {
    
    protected $auth_actions = ['index', 'logout'];

    public function registerAction() {
        if ($this->session->isAuthenticated()) {
            return $this->redirect( '/' );
        }
        $user_name = '';
        $password = '';
        $errors = [];

        if ($this->request->isPost()) {
            $token = $this->request->getPost ('_token');
            if (!$this->checkCsrfToken('user/register', $token)) {
                return $this->redirect('/user/register');
            }
            $user_name = $this->request->getPost ('user_name');
            $password = $this->request->getPost('password');

            if (!strlen($user_name)) {
                $errors[] = 'ユーザIDを入力してください';
            } else if (!preg_match('/^\w{3,20}$/', $user_name)) {
                $errors[] = 'ユーザIDは半角英数およびアンダースコアを3～20文字以内で入力してください';
            } else if (!$this->db_manager->get('User')->isUniqueUserName($user_name)) {
                $errors[] = 'ユーザIDは既に使用されています';
            }

            if (!strlen($password)) {
                $errors[] = 'パスワードを入力してください';
            } else if (4 > strlen($password) || strlen($password) > 30) {
                $errors[] = 'パスワードは4～30文字以内で入力してください';
            }

            if(count($errors) === 0) {
                $this->db_manager->get('User')->insert($user_name, $password);
                $this->session->setAuthenticated(true);

                $user = $this->db_manager->get('User')->fetchByUserName($user_name);
                $this->session->set('user', $user);
                return $this->redirect('/');
            }
        }
        return $this->render([
            'user_name'     => $user_name,
            'password'      => $password,
            'errors'        => $errors,
            '_token'        => $this->generateCsrfToken('user/register')
        ], 'register');
    }
    
    public function indexAction() {
        $message = '';
        $user = $this->session->get('user');
        if ($this->request->isPost()) {
            $data['id'] = $user['id'];
            $data['name'] = $this->request->getPost('name');
            $this->db_manager->get('User')->update($data);
            $user = $this->db_manager->get('User')->fetchByUserName($user['user_name']);
            $this->session->set('user', $user);
            $message = '更新しました';
        }
        return $this->render([
            'user'      => $user,
            'message'   => $message,
        ]);
    }

    public function loginAction() {
        //認証済みならHOME画面へ遷移
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }
        $user_name = '';
        $password = '';
        $errors = [];

        if ($this->request->isPost()) {
            $token = $this->request->getPost('_token');
            if (!$this->checkCsrfToken('user/login', $token)) {
                return $this->redirect('/user/login');
            }
            $user_name = $this->request->getPost('user_name');
            $password = $this->request->getPost('password');
            if (!strlen($user_name)) {
                $errors[] = 'ユーザIDを入力してください';
            }

            if (!strlen($password)) {
                $errors[] = 'パスワードを入力してください';
            }

            if (count($errors) === 0) {
                $user_repository = $this->db_manager->get('User');
                $user = $user_repository->fetchByUserName($user_name);
                if (!$user || ($user['password'] !== $user_repository->hashPassword($password))) {
                    $errors[] = 'ユーザIDかパスワードが不正です';
                } else {
                    //認証OKなのでホーム画面遷移
                    $this->session->setAuthenticated(true);
                    $this->session->set('user', $user);
                    return $this->redirect('/');
                }
            }
        }
        return $this->render([
            'user_name'     => $user_name,
            'password'      => $password,
            'errors'        => $errors,
            '_token'        => $this->generateCsrfToken('user/login')
        ]);
    }

    public function logoutAction() {
        $this->session->clear();
        $this->session->setAuthenticated(false);
        return $this->redirect('/user/login');
    }
}