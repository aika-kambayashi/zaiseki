<?php

class MessageController extends Controller {

    protected $auth_actions = ['index', 'add'];
    protected $msec_list = [
        '電話',
        '伝言',
        '折り返し',
        '来訪'
    ];

    public function indexAction() {
        $user = $this->session->get('user');
        $messages = $this->db_manager->get('message')->getMessageByToUserId($user['id']);
        if ($this->request->isPost()) {
            $delete_flg = false;
            $delete = $this->request->getPost('delete');
            foreach ($delete as $message_id => $d) {
                if ($d[0] == 1) {
                    $this->db_manager->get('message')->deleteByMessageIdAndToUserId($message_id, $user['id']);
                    $delete_flg = true;
                }
            }
            if ($delete_flg) {
                $this->session->set('message', '伝言メモを削除しました');
            } else {
                $this->session->set('message', '伝言メモを残しています');
            }
            return $this->redirect('/');
        }
        return $this->render([
            'messages'  => $messages,
            'user'      => $user,
            'msec_list' => $this->msec_list
        ]);
    }

    public function addAction() {
        $user = $this->session->get('user');
        $message = $this->db_manager->get('message')->getModel();
        if ($this->request->isPost()) {
            $keys = array_keys($message);
            foreach ($keys as $key) {
                $message[$key] = $this->request->getPost($key);
            }
            if (!$this->db_manager->get('user')->fetchByUserId($message['to_user_id'])) {
                $this->session->set('message', '。。ユーザが存在しません');
                return $this->redirect('/');
            }
            $message['from_user_id'] = $user['id'];
            $this->db_manager->get('message')->insert($message);
            $this->session->set('message', '伝言メモを登録しました');
            return $this->redirect('/');
        } else {
            $to_user_id = $this->redirect->getGet('to_user_id');
            $to_user = $this->db_manager->get('user')->fetchByUserId($to_user_id);
            if (!$to_user) {
                $this->session->set ('message', '。。ユーザが存在しません');
                return $this->redirect('/');
            }
            $message['to_user_id'] = $to_user_id;
            $message['to_user_name'] = $to_user['name'];
        }
        return $this->render([
            'message'   => $message,
            'msec_list' => $this->msec_list
        ]);
    }
}