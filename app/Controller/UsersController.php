<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'logout', 'change', 'pass');
	}

	public function isAuthorized($user) {
		if ($this->action === 'view') {
			return true;
		}
		if ($this->Auth->user('id') === $user['id']) {
			if ($this->action === 'edit') {
				return true;
			}
		}
		return parent::isAuthorized($user);
	}

	public function login() {
		if (!$this->Session->read('Auth.User.id')) {
			if ($this->request->is('post')) {
				if ($this->Auth->login()) {
					$this->redirect($this->Auth->redirect());
				} else {
					$this->Flash->error(__('Invalid email or password, try again'));
				}
			}
		} else {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
	}
	public function logout() {
		$this->redirect($this->Auth->logout());
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if (!$this->Session->read('Auth.User.id')) {
			if ($this->request->is('post')) {
				$this->User->create();
				if ($this->User->save($this->request->data)) {
					$this->Flash->success(__('The user has been saved.'));
					return $this->redirect(array('action' => 'login'));
				} else {
					$this->Flash->error(__('The user could not be saved. Please, try again.'));
				}
			}
		} else {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
		$user_id = $this->Session->read('Auth.User.id');
		if ($id === $user_id) {
			if ($this->request->is('submit') || $this->request->is('put')) {
				$uniqid = uniqid(mt_rand(), true);
				$tmp_name = $this->request->data['User']['image']['tmp_name'];
				$image_name = $this->request->data['User']['image']['name'];
				$comment = $this->request->data['User']['comment'];;
				if (empty($comment)) {
					$this->request->data['User']['comment'] = null;
				}
				if ($image_name) {
					$this->request->data['User']['image'] = $image_name;
					if (!getimagesize($tmp_name)) {
						$this->Flash->error(__('File Error.'));
						return $this->redirect(array('action' => 'view', $id));
					}
					$extension = image_type_to_extension(exif_imagetype($tmp_name));
					move_uploaded_file($tmp_name, '../webroot/img/' . $uniqid . $extension);
					if ($this->request->data['User']['image_before']) {
						$image_before = $this->request->data['User']['image_before'];
						unlink('../webroot/img/' . $image_before);
					}
				} else {
					$this->User->save($this->request->data, false, array('comment', 'id'));
					$this->Flash->success(__('The user has been updated.'));
					return $this->redirect(array('action' => 'view', $id));
				}
				$this->request->data['User']['image'] = $uniqid . $extension;
				if ($this->User->save($this->request->data, array('image', 'id'))) {
					$this->Flash->success(__('The user has been updated.'));
					return $this->redirect(array('action' => 'view', $id));
				}
				$this->Flash->error(__('The user could not be updated. Please try again'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->request->data = $this->User->findById($id);
				unset($this->request->data['User']['password']);
			}
		} else {
			$this->Flash->error(__('Only the person can edit.'));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index', $id));
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete($id)) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function change() {
		if ($this->request->is('post')) {
			$email = $this->request->data['User']['email'];
			if ($email) {
				if ($this->request->data = $this->User->findByEmail($email)) {
					$pass_reset = md5(uniqid(rand(), true));
					$url = "https://procir-study.site/Yamaguchi418/cakephp/users/pass?pass_reset=" . $pass_reset;
					$subject = "[Procir-cakePHP]Change password URL";
					$message = "Please access the URL below to change your password." . "\r\n" . $url . "\r\n" . "Password change deadline is within 30 minutes";
					$send_email = new CakeEmail();
					$send_email->from('ky02161988@outlook.jp');
					$send_email->to($email);
					$send_email->subject($subject);
					$send_email->send($message);
					$this->request->data['User']['pass_reset'] = $pass_reset;
					$this->request->data['User']['pass_time'] = DboSource::expression('NOW()');
					$this->User->save($this->request->data, false, array('pass_reset', 'pass_time', 'id'));
					$this->Flash->success(__('The user has been requested. Please check your email.'));
					return $this->redirect(array('action' => 'login'));
				} else {
					$this->Flash->error(__('Email does not exist. Please, try again.'));
				}
			} else {
				$this->Flash->error(__('It is blank. Please, try again.'));
			}
		}
	}

	public function pass() {
		if ($this->request->is('post')) {
			$password = $this->request->data['User']['password'];
			if ($password) {
				$pass_reset = Hash::get($this->request->query, 'pass_reset');
				if ($this->request->data = $this->User->findByPass_reset($pass_reset)) {
					$now = date('Y-m-d H:i:s');
					$pass_time = $this->request->data['User']['pass_time'];
					$target_time = date("Y-m-d H:i:s", strtotime($pass_time . "+30 minute"));
					if ($target_time > $now) {
						$this->request->data['User']['pass_reset'] = null;
						$this->request->data['User']['pass_time'] = null;
						$this->request->data['User']['password'] = $password;
						$this->User->save($this->request->data, false, array('password', 'pass_reset', 'pass_time'));
						$this->Flash->success(__('The user has been saved.'));
						unset($this->request->data['User']['password']);
						return $this->redirect(array('action' => 'login'));
					} else {
						$this->Flash->error(__('Invalid URL. Please, try again.'));
						return $this->redirect(array('action' => 'login'));
					}
				} else {
					$this->Flash->error(__('Invalid URL. Please, try again.'));
					return $this->redirect(array('action' => 'login'));
				}
			} else {
				$this->Flash->error(__('It is blank. Please, try again.'));
			}
		}
	}
}
