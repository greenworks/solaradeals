<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

?>

<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">	
		<tr>
			<th width="5"><?php echo JText::_( 'MENU_ID' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>			
			<th width="200"><?php echo JText::_( 'MENU_TITLE' ); ?></th>
			<th width="200"><?php echo JText::_( 'MENU_META_KEYWORDS_HEADER' ); ?></th>
			<th width="200"><?php echo JText::_( 'MENU_META_DESC_HEADER' ); ?></th>			
			<th width="200"><?php echo JText::_( 'MENU_MENUTYPE' ); ?></th>			
		</tr>				
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = & $this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_sjmeta&controller=menumeta&task=edit&cid[]='. $this->items[$i]->secid );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->items[$i]->secid; ?></td>
			<td><?php echo $checked; ?></td>
			<td><a href="<?php echo $link; ?>"><?php echo $this->items[$i]->title; ?></a></td>
			<td><?php echo $row->menu_meta_keywords; ?></td>
			<td><?php echo $row->menu_meta_desc; ?></td>			
			<td><?php echo $row->menutype; ?></td>			
		</tr>
		
		<?php		
		$k = 1 - $k;
	}
	?>
	 <tfoot>
    <tr>
      <td colspan="6">
      	<?php echo $this->pagination->getListFooter(); ?>
      </td>
    </tr>
  </tfoot>
	</table>
</div>

<input type="hidden" name="option" value="com_sjmeta" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="view" value="menumeta" />
<input type="hidden" name="controller" value="menumeta" />
</form>


