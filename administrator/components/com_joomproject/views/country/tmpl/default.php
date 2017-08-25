<?php 
 /**
* @package   Dowalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 
defined('_JEXEC') or die('Restricted access');
$uri = JURI::getInstance();
$url= $uri->root();
$ordering = ($this->lists['order'] == 'ordering');

$option = JRequest::getVar('option');
$filter = JRequest::getVar('filter'); 
$search_word  = JRequest::getVar('search_word','','request','string');
$document =  JFactory::getDocument();
$document->addScript($url.'administrator/components/'.$option.'/assets/js/fields.js');
$listOrder	= $this->escape($this->lists['order']);
$listDirn	= $this->escape($this->lists['order_Dir']);


// ================================= For Ordering =======================================================//
$listOrder	= $this->escape($this->lists['order']);
$listDirn	= $this->escape($this->lists['order_Dir']);
$saveOrder	= $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?tmpl=component&option='.$option.'&view=country&task=saveOrderAjax';
	JHtml::_('sortablelist.sortable', 'list2', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
// ================================= For Ordering =======================================================// 


?>

<script language="javascript" type="text/javascript">

Joomla.submitform =function submitform(pressbutton){
var form = document.adminForm;
if (pressbutton){
 form.task.value=pressbutton;
 }
 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
  ||(pressbutton=='remove')|| (pressbutton=='copy') )
 {   
  form.view.value="country_detail";
 }
 try {
  form.onsubmit();
 }
catch(e){}
form.submit();
}
 
function submitform(pressbutton){
var form = document.adminForm;
 if (pressbutton){
   form.task.value=pressbutton;
 }
 if ((pressbutton=='publish')||(pressbutton=='unpublish') ||(pressbutton=='saveorder'))
 {   
   form.view.value="country_detail";
  }
 try {
  form.onsubmit();
  }
 catch(e){}
 form.submit();
}
 
function selectsearch() {
//	alert('aavyu');
	var form = document.adminForm;
	form.submit();
}
function get_select() {
	var form = document.adminForm;
	form.submit();
}
function get_select1() {
	var form = document.adminForm;
	form.submit();
} 
</script>


<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" id="adminForm" >
<div id="filter-bar" class="btn-toolbar">
	<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			
		<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_( 'FILTER' ); ?>:</label>
				<input type="text" name="search_word" id="filter_search" style="width:150px;" value="<?php echo $search_word;?>" />
		</div>
		<div class="btn-group pull-left hidden-phone">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
	</div>
 
	<table class="table table-striped">
	<thead>
			<tr>
		
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%" class="title">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);"/>
			</th>
					 
			<th class="center">
				<?php echo JHTML::_('grid.sort','COUNTRY_NAME', 'country_name', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>

			<th class="center">
				<?php echo JHTML::_('grid.sort','COUNTRY_3_CODE', 'country_3_code', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>

			<th class="center">
				<?php echo JHTML::_('grid.sort','COUNTRY_2_CODE', 'country_2_code', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
		
		
			<th width="5%" style="min-width:55px" class="nowrap center">
				<?php echo JHtml::_('grid.sort', 'PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>			
			
			
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->country ); $i < $n; $i++)
	{
		$row = &$this->country[$i];
        $row->id = $row->country_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=country_detail&task=edit&cid[]='. $row->country_id );
		
		$published 	= JHTML::_('grid.published', $row, $i );		
		
		?>
		<tr class="<?php echo "row$k"; ?>">
	
			<td class="order">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td class="order">
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td class="center">
			<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_TAG' ); ?>"><?php echo $row->country_name; ?></a>
			</td>			
			 
			<td class="center">
				<?php echo $row->country_3_code; ?>
			</td>
			<td class="center">
				<?php echo $row->country_2_code; ?>
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

<input type="hidden" name="view" value="country" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
