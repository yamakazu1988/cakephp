<div>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend><?php echo __('Add User'); ?></legend>
<?php
echo $this->Form->input('username');
echo $this->Form->input('email');
echo $this->Form->input('password');
?>
</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<p><?php echo $this->Html->link('back', array('controller' => 'users', 'action' => 'login')); ?></p>
