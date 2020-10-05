<div>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend><?php echo __('Change password'); ?></legend>
<?php
echo $this->Form->input('password', array('value' => ''));
?>
</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<p><?php echo $this->Html->link('back', array('controller' => 'users', 'action' => 'login')); ?></p>
