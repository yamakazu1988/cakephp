<!-- File: /app/View/Posts/index.ctp -->

<h1>Blog posts</h1>
<p><?php echo $this->Html->link('Add Post', array('controller' => 'posts', 'action' => 'add')); ?></p>
<table>
<tr>
<th>Id</th>
<th>username</th>
<th>Title</th>
<th>Action</th>
<th>Created</th>
</tr>

<!-- ここから、$posts配列をループして、投稿記事の情報を表示 -->

<?php foreach ($posts as $post): ?>
<tr>
<td><?php echo $post['Post']['id']; ?></td>
<td><?php echo $post['User']['username']; ?></td>
<td>
<?php echo $this->Html->link($post['Post']['title'],array('controller' => 'posts', 'action' => 'view', $post['Post']['id'])); ?>
</td>
<?php if (!empty($this->Session->read('Auth.User')) && (($this->Session->read('Auth.User.id')) === $post['Post']['user_id'])): ?>
<td>
<?php echo $this->Form->postLink('Delete', array('action' => 'delete', $post['Post']['id']), array('confirm' => 'Are you sure?')); ?>
/
<?php echo $this->Html->link('Edit', array('action' => 'edit', $post['Post']['id'])); ?>
</td>
<?php else: ?>
<td></td>
<?php endif; ?>
<td><?php echo $post['Post']['created']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php if (!empty($this->Session->read('Auth.User'))): ?>
<p><?php echo $this->Html->link('logout', array('controller' => 'users', 'action' => 'logout')); ?></p>
<?php else: ?>
<p><?php echo $this->Html->link('back', array('controller' => 'users', 'action' => 'login')); ?></p>
<?php endif; ?>
