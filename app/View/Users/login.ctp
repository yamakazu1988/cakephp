<div>
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend>
<?php echo __('Please enter your e email and password'); ?>
</legend>
<?php
echo $this->Form->input('email');
echo $this->Form->input('password');
?>
</fieldset>
<?php echo $this->Form->end(__('Login')); ?>
<p><?php echo $this->Html->link(__('List Posts'), array('controller' => 'posts', 'action' => 'index')); ?> </p>
<p><?php echo $this->Html->link(__('Sing up'), array('action' => 'add')); ?></p>
</div>
