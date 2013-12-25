<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );
global $mainframe;
$editor		=& JFactory::getEditor();

$menu_id = JRequest::getVar('cid');

?>
	
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend>
			<?php echo JText::_( 'MENU_META_DETAILS' ).' for '.$this->menuname; 	?>
		</legend>

		<table class="admintable">
		<?php if($this->menumeta->title) { ?>	
		<tr>
			<td width="100" align="right" class="key">
				<label for="menu">
					<?php echo JText::_( 'MENU_TITLE' ); ?>:
				</label>
			</td>
			<td><?php echo $this->menumeta->title;?></td>
		</tr>	
		<?php } ?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="keywords">
					<?php echo JText::_( 'MENU_META_KEYWORDS' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="menu_meta_keywords" id="menu_meta_keywords" size="32" maxlength="250" value="<?php echo $this->menumeta->menu_meta_keywords;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'MENU_META_DESC' ); ?>:
				</label>
			</td>
			<td><textarea name="menu_meta_desc" rows="3" cols="40"><?php echo $this->menumeta->menu_meta_desc; ?></textarea></td>
		</tr>

	</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_sjmeta" />
<input type="hidden" name="id" value="<?php echo $this->menumeta->id; ?>" />
<input type="hidden" name="menu_id" value="<?php echo $menu_id[0]; ?>">
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="menumeta" />
<input type="hidden" name="controller" value="menumeta" />
</form>

