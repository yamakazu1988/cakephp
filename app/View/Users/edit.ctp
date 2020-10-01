<div>
<?php echo $this->Form->create('User', array('enctype' => 'multipart/form-data')); ?>
<legend><?php echo __('Edit User'); ?></legend>
<dl>
<dt><?php echo __('Username'); ?></dt>
<dd>
<?php echo h($user['User']['username']); ?>
&nbsp;
</dd>
</dl>
<fieldset>
<?php echo $this->Form->input('comment'); ?>
<?php echo $this->Form->input('image_name', array('label' => 'image', 'accept' => 'image/png, image/jpeg', 'type' => 'file')); ?>
</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
<p><?php echo $this->Html->link('back', array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?></p>
</div>
