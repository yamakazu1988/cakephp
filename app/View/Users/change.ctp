<div>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<?php echo __('Please enter your email'); ?>
<legend><?php echo __('Password change request'); ?></legend>
<?php
echo $this->Form->input('email');
?>
</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<p><?php echo $this->Html->link('back', array('controller' => 'users', 'action' => 'login')); ?></p>
