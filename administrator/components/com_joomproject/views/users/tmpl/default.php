<?php 
/**
* @package   JE CATEGORY component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access');
$option = JRequest::getVar('option','','request','string');
$filter = JRequest::getVar('filter');
JHTML::_('behavior.calendar');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.combobox');
JHtml::_('behavior.keepalive');
$uri = JURI::getInstance();
$url= $uri->root();
$ordering = ($this->lists['order'] == 'ordering');
JHTML::_('behavior.tooltip');
$model = $this->getModel('category');
$serach  = JRequest::getVar('serach','','request','string');
 
// ================================= For Ordering =======================================================//

$listOrder	= $this->escape($this->lists['order']);

$listDirn	= $this->escape($this->lists['order_Dir']);

$saveOrder	= $listOrder == 'ordering';

if ($saveOrder)

{

	$saveOrderingUrl = 'index.php?tmpl=component&option='.$option.'&view=subscription&task=saveOrderAjax';

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

<!---------------------------------------- End Ordering Script ------------------------------------------ -->
<script language="javascript" type="text/javascript">
	Joomla.submitform =function submitform(pressbutton){
		var form = document.adminForm;
		if (pressbutton)
		{form.task.value=pressbutton;}
		if ((pressbutton=='add')||(pressbutton=='edit') ||(pressbutton=='remove')||(pressbutton=='publish')||(pressbutton=='unpublish')){		 
		form.view.value="category_detail";
		} try {
			form.onsubmit();
		}
		catch(e){}
		form.submit();
	}
	
	function submitform(pressbutton){
		var form = document.adminForm;
		if (pressbutton)
		{form.task.value=pressbutton;}
		if ((pressbutton=='publish')||(pressbutton=='unpublish'))
		{		 
		form.view.value="category_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	form.submit();
	}
	
function selectsearch() {
	var form = document.adminForm;
	form.submit();

}

 </script>
<style>
body {
    line-height: 4px !important;
}
</style>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" id="adminForm" >
<table align="right">
    <tr>
         <td>
         	<div id="filter-bar" class="btn-toolbar">
		  <div class="filter-search btn-group pull-left">
            <label for="filter_search" class="element-invisible"><?php //echo JText::_( 'COM_JERECIPES_FILTER' ); ?>:</label>
            <input type="text" name="serach" id="filter_search"  placeholder = "<?php echo JText::_('SEARCH_BY_CATEGORY_NAME');?>" style="width:170px;" value="<?php echo $serach;?>" />
          </div>
          <div class="btn-group pull-left hidden-phone">
            <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
            <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
           </div>
           </div>
         </td>
         
         <td>
		 	<?php echo $this->searchlists['published']; ?>
         </td>
         <td>
         	<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
         </td>
    </tr>
</table>
<br/>
<table   class="table table-striped" id="list2">
	<thead>
		<tr>
		<!----------------------------------------For Ordering ------------------------------------------>

			<th width="1%" class="nowrap center hidden-phone">

				<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>

			</th>

			<!----------------------------------------For Ordering ------------------------------------------>
			<th width="3%">
				<?php echo JText::_('NUM'); ?>
			</th>
			<th width="3%" class="title">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th >
			<th class="title"  width="20%" align="center">
				<?php echo JHTML::_('grid.sort', 'CNAME', 'cname', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
		 
			
			<th width="3%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>					 
		</tr>
	</thead>
	<?php
		$k = 0;
		for ($i=0, $n=count( $this->tour ); $i < $n; $i++)
		{
			$row = &$this->tour[$i];
			$row->id;
			$link 	= JRoute::_( 'index.php?option='.$option.'&view=category_detail&task=edit&cid[]='. $row->id );
			$published 	= JHTML::_('grid.published', $row, $i );		
	?>
		  	<tr class="row<?php echo $i % 2; ?>" itemID="<?php echo $row->id;?>">

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
			<td align="center">
				<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_TAG' ); ?>"><?php echo $row->cname	; ?></a>
			</td>
            
          
			<td align="center">
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
<input type="hidden" name="id" value="" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="option" value="com_jequoteproduct" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>