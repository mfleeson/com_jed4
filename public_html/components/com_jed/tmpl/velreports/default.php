<?php
/**
 * @package       JED
 *
 * @subpackage    VEL
 *
 * @copyright     Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;
use Jed\Component\Jed\Site\Helper\JedHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_jed') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'velreportform.xml');
$canEdit    = $user->authorise('core.edit', 'com_jed') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'velreportform.xml');
$canCheckin = $user->authorise('core.manage', 'com_jed');
$canChange  = $user->authorise('core.edit.state', 'com_jed');
$canDelete  = $user->authorise('core.delete', 'com_jed');
$isLoggedIn  = JedHelper::IsLoggedIn();
$redirectURL = JedHelper::getLoginlink();

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_jed/css/list.css');
if (!$isLoggedIn)
{
	$app = JFactory::getApplication();

	$app->enqueueMessage(Text::_('COM_JED_VEL_REPORTS_NO_ACCESS'), 'success');
	$app->redirect($redirectURL);

}
else
{
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
        <div class="table-responsive">
	<table class="table table-striped" id="velreportList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				
			<?php endif; ?>

							<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_JED_VEL_REPORT_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>
				
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_JED_VEL_REPORT_LIST_VULNERABLE_ITEM_NAME_LABEL', 'a.vulnerable_item_name', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_JED_VEL_REPORT_LIST_VULNERABLE_ITEM_VERSION_LABEL', 'a.vulnerable_item_version', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_JED_VEL_REPORT_LIST_EXPLOIT_TYPE_LABEL', 'a.exploit_type', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_JED_VEL_REPORT_LIST_CONSENT_TO_PROCESS_LABEL', 'a.consent_to_process', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_JED_VEL_REPORT_LIST_PASSED_TO_VEL_LABEL', 'a.passed_to_vel', $listDirn, $listOrder); ?>
				</th>
	
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_JED_VEL_REPORT_LIST_DATE_SUBMITTED_LABEL', 'a.date_submitted', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo Text::_('COM_JED_VEL_REPORT_LIST_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_jed'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_jed')): ?>
					<?php $canEdit = Factory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					
				<?php endif; ?>

								<td>
<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'velreports.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo Route::_('index.php?option=com_jed&view=velreport&id='.(int) $item->id); ?>">
                    <?php echo $item->id; ?></a>
				</td>
				
				<td>

					<?php echo $item->vulnerable_item_name; ?>
				</td>
				<td>

					<?php echo $item->vulnerable_item_version; ?>
				</td>
				<td>

					<?php echo $item->exploit_type; ?>
				</td>
				<td>

					<?php echo $item->consent_to_process; ?>
				</td>
				<td>

					<?php echo $item->passed_to_vel; ?>
				</td>

				<td>

					<?php
					$date = $item->date_submitted;
					echo $date > 0 ? HTMLHelper::_('date', $date, Text::_('DATE_FORMAT_LC6')) : '-';
					?>				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo Route::_('index.php?option=com_jed&task=velreport.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo Route::_('index.php?option=com_jed&task=velreportform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_jed&task=velreportform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_JED_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo Text::_('COM_JED_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; 
}
?>
