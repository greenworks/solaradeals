
<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>

<?php
	if(isset($this->message)){
		$this->display('message');
	}
?>
<br/><br/><br/>
<table>
	<tr>
		<td style="width:60%"><br/>
			<p>
				Please register one time to have access to your <br/>
galleries online, this help us to manage your  <br/>
request faster
			</p>
			<br/><br/><br/>
			<h1>Register</h1>
		</td>
		
		<td style="width:40%">
<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td width="30%" height="40">
		<label id="namemsg" for="name">
			<?php echo JText::_( 'Name' ); ?>:
		</label>
	</td>
  	<td width="70%">
  		<input type="text" name="name" id="name"  size="18" style="width:200px;height:14px:font-size:11px"  value="<?php echo $this->escape($this->user->get( 'name' ));?>" class="inputbox required" maxlength="50" style="width:200px;height:14px:font-size:11px" /> *
  	</td>
</tr>
<tr>
	<td height="40">
		<label id="usernamemsg" for="username">
			<?php echo JText::_( 'User name' ); ?>:
		</label>
	</td>
	<td>
		<input type="text" id="username" name="username"  size="18" style="width:200px;height:14px:font-size:11px"  value="<?php echo $this->escape($this->user->get( 'username' ));?>" class="inputbox required validate-username" maxlength="25" style="width:200px;height:14px:font-size:11px" /> *
	</td>
</tr>
<tr>
	<td height="40">
		<label id="emailmsg" for="email">
			<?php echo JText::_( 'Email' ); ?>:
		</label>
	</td>
	<td>
		<input type="text" id="email" name="email"  size="18" style="width:200px;height:14px:font-size:11px"  value="<?php echo $this->escape($this->user->get( 'email' ));?>" class="inputbox required validate-email" maxlength="100" style="width:200px;height:14px:font-size:11px"  /> *
	</td>
</tr>
<tr>
	<td height="40">
		<label id="pwmsg" for="password">
			<?php echo JText::_( 'Password' ); ?>:
		</label>
	</td>
  	<td>
  		<input class="inputbox required validate-password" type="password" id="password" name="password"  size="18" style="width:200px;height:14px:font-size:11px"  value="" style="width:200px;height:14px:font-size:11px" /> *
  	</td>
</tr>
<tr>
	<td height="40">
		<label id="pw2msg" for="password2">
			<?php echo JText::_( 'Verify Password' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox required validate-passverify" type="password" id="password2" name="password2"  size="18" style="width:200px;height:14px:font-size:11px"  value="" style="width:200px;height:14px:font-size:11px"  /> *
	</td>
</tr>
<tr>
	<td colspan="2" height="40">
		<?php echo JText::_( 'REGISTER_REQUIRED' ); ?>
	</td>
</tr>
</table>
  <br/>
	<button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
	<input type="hidden" name="task" value="register_save" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="gid" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</td>
</tr>
</table>