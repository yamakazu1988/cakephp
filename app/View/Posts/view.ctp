<!-- File: /app/View/Posts/view.ctp -->

<h1><?php echo h($post['Post']['title']); ?></h1>

<p><small>Created: <?php echo $post['Post']['created_at']; ?></small></p>

<p><?php echo h($post['Post']['message']); ?></p>
