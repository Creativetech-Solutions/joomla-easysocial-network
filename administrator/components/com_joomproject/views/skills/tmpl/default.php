<?php 
/**
* @package   JE Auto
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/
defined('_JEXEC') or die('Restricted access');
 
$option = JRequest::getVar('option','','request','string');
$ordering = ($this->lists['order'] == 'ordering');
$filter = JRequest::getVar('filter');
$model = $this->getModel('skills'); 

// ================================= For Ordering =======================================================//
$listOrder	= $this->escape($this->lists['order']);
$listDirn	= $this->escape($this->lists['order_Dir']);
$saveOrder	= $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?tmpl=component&option='.$option.'&view=skills&task=saveOrderAjax';
	JHtml::_('sortablelist.sortable', 'list2', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
// ================================= For Ordering =======================================================// 
?>
<!---------------------------------------- Ordering Script ------------------------------------------>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<!---------------------------------------- End Ordering Script ------------------------- skills_detail ----------------->
<script language="javascript" type="text/javascript">
 Joomla.submitform =function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove')|| (pressbutton=='copy') )
	 {		 
	  form.view.value="skills_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}

function mysubmit_form(myform_id) {
	var form = document.adminForm;
	form.submit();
}

</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" id="adminForm" >
<div id="editcell">
 
		<table class="table table-striped" id="list2">
	<thead>
		<tr>
			<!----------------------------------------For Ordering ------------------------------------------>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
			</th>
			<!----------------------------------------For Ordering ------------------------------------------>
			<th class="hidden-phone" width="1%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%" class="hidden-phone">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th >
			<th class="hidden-phone"  width="20%">
				<?php echo JHTML::_('grid.sort', 'ID', 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
			<th class="hidden-phone"  width="10%">
				<?php echo JHTML::_('grid.sort', 'SKILL_TITLE', 'skill_title', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
			<th  style="min-width:55px" class="nowrap center">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $listDirn, $listOrder ); ?>	
			</th>					 
			 
					
		</tr>
	</thead>
	<?php
	$k = 0;
	// echo '<pre>'; print_r($this->skills); exit;
	for ($i=0, $n=count( $this->skills ); $i < $n; $i++)
	{
	
		$row = &$this->skills[$i];
        $row->id = $row->id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=skills_detail&task=edit&cid[]='. $row->id );
		$published 	= JHTML::_('grid.published', $row, $i );		
		
		?>
		<tr class="row<?php echo $i % 2; ?>" itemid="<?php echo $row->ordering;?>">
				<!----------------------------------------For Ordering ------------------------------------------>
			<td class="order nowrap center hidden-phone">
			<?php 
				$disableClassName = '';
				$disabledLabel	  = '';

				if (!$saveOrder) :
					$disabledLabel    = JText::_('JORDERINGDISABLED');
					$disableClassName = 'inactive tip-top';
				endif; ?>
				<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
					<i class="icon-menu"></i>
				</span>
				<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order " />
		</td>
			<!----------------------------------------For Ordering ------------------------------------------>
			<td class="order">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td class="order">
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td>
			<?php echo $row->id; ?>
			</td>
			<td>
					<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_TAG' ); ?>"><?php  echo $row->skill_title; ?></a>
			</td>
			
			
			
			<td class="center">
			<div class="btn-group">
				<?php echo JHtml::_('jgrid.published', $row->published, $i, '', 'cb'); ?>
			</div>
		</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>	

<tfoot>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>
</div>

<input type="hidden" name="view" value="skills" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
