<?php


/**
* @package		SCMeta
* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link     http://www.techjoomla.com
**/


// no direct access
defined( '_JEXEC' ) or die( ';)' );
global $mainframe;
$editor		=& JFactory::getEditor();

$section_id = JRequest::getVar('cid');

?>
	
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend>
			<?php echo JText::_( 'SECTION_META_DETAILS' ).' for '.$this->sectionname; 	?>
		</legend>

		<table class="admintable">
		<?php if($this->sectionmeta->title) { ?>	
		<tr>
			<td width="100" align="right" class="key">
				<label for="section">
					<?php echo JText::_( 'SECTION_TITLE' ); ?>:
				</label>
			</td>
			<td><?php echo $this->sectionmeta->title;?></td>
		</tr>	
		<?php } ?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="keywords">
					<?php echo JText::_( 'SECTION_META_KEYWORDS' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="section_meta_keywords" id="section_meta_keywords" size="32" maxlength="250" value="<?php echo $this->sectionmeta->section_meta_keywords;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'SECTION_META_DESC' ); ?>:
				</label>
			</td>
			<td><textarea name="section_meta_desc" rows="3" cols="40"><?php echo $this->sectionmeta->section_meta_desc; ?></textarea></td>
		</tr>

	</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_scmeta" />
<input type="hidden" name="id" value="<?php echo $this->sectionmeta->id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id[0]; ?>">
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="sectionmeta" />
<input type="hidden" name="controller" value="sectionmeta" />
</form>

