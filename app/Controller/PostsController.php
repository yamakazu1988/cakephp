<?php
class PostsController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Flash', 'Session');

	public function index() {
		$this->set('posts', $this->Post->find('all'));
		$this->set('users', $this->Post->User->find('list',array('fields' => array('User.username'))));
	}

	public function view($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Post->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}
		$this->set('post', $post);
	}

	public function add() {
		$username = $this->Session->read('Auth.User.username');
		if ($this->request->is('post')) {
			$this->request->data['Post']['user_id'] = $this->Auth->user('id');
			if ($this->Post->save($this->request->data)) {
				$this->Flash->success(__('Your post has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
		}
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}
		$post = $this->Post->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$post_id = $this->request->data['Post']['id'];
			$this->Post->id = $id;
			if ($post_id === $id) {
				if ($this->Post->save($this->request->data)) {
					$this->Flash->success(__('Your post has been updated.'));
					return $this->redirect(array('action' => 'index'));
				}
				$this->Flash->error(__('Unable to update your post.'));
			} else {
				$this->Flash->error(__('Unexpected error, Unable to update your post.'));
				return $this->redirect(array('action' => 'index'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		if ($this->Post->delete($id)) {
			$this->Flash->success(__('The post with id: %s has been deleted.', h($id)));
		} else {
			$this->Flash->error(__('The post with id: %s could not be deleted.', h($id)));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function isAuthorized($user) {
		if ($this->action === 'add') {
			return true;
		}
		if (in_array($this->action, array('edit', 'delete'))) {
			$postId = (int) $this->request->params['pass'][0];
			if ($this->Post->isOwnedBy($postId, $user['id'])) {
				return true;
			} else {
				return $this->redirect(array('action' => 'index'));
			}
		}
		return parent::isAuthorized($user);
	}
}
?>
