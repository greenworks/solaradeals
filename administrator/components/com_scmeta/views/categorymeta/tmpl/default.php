<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );
?>

<script language="javascript" type="text/javascript">
function tableOrdering( order, dir, task )
{
        var form = document.adminForm;
 
        form.cat_filter_order.value = order;
        form.cat_filter_order_Dir.value = dir;
        document.adminForm.submit( task );
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">
	<table class="adminlist">	
		<tr>
			<th width="5"><?php echo JText::_( 'CATEGORY_ID' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>			
			<th width="200"><?php echo JHTML::_( 'grid.sort', 'Category Title', 'cat.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<th width="200"><?php echo JText::_( 'CATEGORY_META_KEYWORDS_HEADER' ); ?></th>
			<th width="200"><?php echo JText::_( 'CATEGORY_META_DESC_HEADER' ); ?></th>	
			<th width="200"><?php echo JText::_( 'SECTION_TITLE' ); ?></th>					
		</tr>				
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = & $this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_scmeta&controller=categorymeta&task=edit&cid[]='. $this->items[$i]->catid );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->items[$i]->catid; ?></td>
			<td><?php echo $checked; ?></td>
			<td><a href="<?php echo $link; ?>"><?php echo $this->items[$i]->title; ?></a></td>
			<td><?php echo $row->category_meta_keywords; ?></td>
			<td><?php echo $row->category_meta_desc; ?></td>
			<td><?php echo $row->section_title; ?></td>			
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

<input type="hidden" name="option" value="com_scmeta" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="view" value="categorymeta" />
<input type="hidden" name="controller" value="categorymeta" />
<input type="hidden" name="cat_filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="cat_filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
