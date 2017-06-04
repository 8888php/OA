
<?php echo $this->Form->create('login', array('type'=>'post'));?>
<input type="text" name="data[user]" id="user" />
<input type="password" name="data[password]" id="password" />
<span><?php echo empty($error) ? '' : $error;?></span>
<?php echo $this->Form->end('submin');?>