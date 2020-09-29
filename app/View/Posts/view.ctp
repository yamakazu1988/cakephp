<!-- File: /app/View/Posts/view.ctp -->
<h1>username</h1>
<p><?php echo h($post['User']['username']); ?></p>
<h1>title</h1>
<p><?php echo h($post['Post']['title']); ?></p>
<h1>message</h1>
<p><?php echo h($post['Post']['message']); ?></p>
<p><?php echo $this->Html->link('back', array('controller' => 'posts', 'action' => 'index')); ?></p>
