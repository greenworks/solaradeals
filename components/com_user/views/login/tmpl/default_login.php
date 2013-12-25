<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang = &JFactory::getLanguage();
		$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
		$langScript = 	'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var comlogin = 1;';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $langScript );
		JHTML::_('script', 'openid.js');
endif; ?>
<br/><br/><br/><br/>
<table>
	<tr>
		<td style="width:60%">
			<p>
				Please register one time to have access to your <br/>
galleries online, this help us to manage your  <br/>
request faster
			</p>
			<br/><br/><br/>
			<h1>Login</h1>
		</td>
		
		<td style="width:40%">
<form action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure')); ?>" method="post" name="com-login" id="com-form-login">

<fieldset class="input">
	<table>
		<tr>
		<td >Username:&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td ><input name="username" id="username" type="text" class="inputbox" alt="username" size="18" style="width:200px;height:14px:font-size:11px" /></td>
	</tr>
	<tr><td colspan=2></td></tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" id="passwd" name="passwd" class="inputbox" size="18" alt="password" style="width:200px;height:14px:font-size:11px" 	 /></td>
	</tr>
	<tr><td colspan=2></td></tr>
	<tr>
		<td colspan=2>Remember me <input type="checkbox" id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" /></td>
		
	</tr>
	<tr><td colspan=2></td></tr>
	<tr>
		<td colspan=2><input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" /></td>
		
	</tr>
</table>
			
	<br/><br/><br/>
</fieldset>
<ul>
	<li>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
		<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
	</li>
	<li>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
		<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
	</li>
	<?php
	$usersConfig = &JComponentHelper::getParams( 'com_users' );
	if ($usersConfig->get('allowUserRegistration')) : ?>
	<li>
		<br/><br/>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
			<?php echo JText::_('REGISTER'); ?></a>
	</li>
	<?php endif; ?>
</ul>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</td>
</tr>
</table>