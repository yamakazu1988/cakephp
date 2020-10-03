<div>
<h2><?php echo __('User'); ?></h2>
<dl>
<dt><?php echo __('Username'); ?></dt>
<dd>
<?php echo h($user['User']['username']); ?>
&nbsp;
</dd>
<dt><?php echo __('picture'); ?></dt>
<dd>
<?php if ($user['User']['image']): ?>
<?php echo $this->Html->image($user['User']['image'], array('width'=>'200', 'height'=>'200')); ?>
<?php else: ?>
<?php echo '未登録'; ?>
<?php endif; ?>
</dd>
<dt><?php echo __('Email'); ?></dt>
<dd>
<?php echo h($user['User']['email']); ?>
&nbsp;
</dd>
<dt><?php echo __('Comment'); ?></dt>
<dd>
<?php echo h($user['User']['comment']); ?>
&nbsp;
</dd>
</dl>
<?php if (($this->Session->read('Auth.User.id')) === $user['User']['id']): ?>
<p><?php echo $this->Html->link('edit', array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></p>
<?php endif; ?>
<p><?php echo $this->Html->link('back', array('controller' => 'posts', 'action' => 'index')); ?></p>
</div>
