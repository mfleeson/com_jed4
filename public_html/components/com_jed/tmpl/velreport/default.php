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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$canEdit = Factory::getUser()->authorise('core.edit', 'com_jed');

if (!$canEdit && Factory::getUser()->authorise('core.edit.own', 'com_jed'))
{
	$canEdit = Factory::getUser()->id == $this->item->created_by;
}
?>

    <div class="item_fields">

        <table class="table">


            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_ID_LABEL'); ?></th>
                <td><?php echo $this->item->id; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_REPORTER_FULLNAME_LABEL'); ?></th>
                <td><?php echo $this->item->reporter_fullname; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_REPORTER_EMAIL_LABEL'); ?></th>
                <td><?php echo $this->item->reporter_email; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_REPORTER_ORGANISATION_LABEL'); ?></th>
                <td><?php echo $this->item->reporter_organisation; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_PASS_DETAILS_OK_LABEL'); ?></th>
                <td><?php echo $this->item->pass_details_ok; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_TYPE_LABEL'); ?></th>
                <td><?php echo $this->item->vulnerability_type; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABLE_ITEM_NAME_LABEL'); ?></th>
                <td><?php echo $this->item->vulnerable_item_name; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABLE_ITEM_VERSION_LABEL'); ?></th>
                <td><?php echo $this->item->vulnerable_item_version; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_EXPLOIT_TYPE_LABEL'); ?></th>
                <td><?php echo $this->item->exploit_type; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_EXPLOIT_OTHER_DESCRIPTION_LABEL'); ?></th>
                <td><?php echo nl2br($this->item->exploit_other_description); ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_DESCRIPTION_LABEL'); ?></th>
                <td><?php echo nl2br($this->item->vulnerability_description); ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_HOW_FOUND_LABEL'); ?></th>
                <td><?php echo nl2br($this->item->vulnerability_how_found); ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_ACTIVELY_EXPLOITED_LABEL'); ?></th>
                <td><?php echo $this->item->vulnerability_actively_exploited; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_PUBLICLY_AVAILABLE_LABEL'); ?></th>
                <td><?php echo $this->item->vulnerability_publicly_available; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_PUBLICLY_URL_LABEL'); ?></th>
                <td><?php echo $this->item->vulnerability_publicly_url; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_SPECIFIC_IMPACT_LABEL'); ?></th>
                <td><?php echo nl2br($this->item->vulnerability_specific_impact); ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DEVELOPER_COMMUNICATION_TYPE_LABEL'); ?></th>
                <td><?php echo $this->item->developer_communication_type; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DEVELOPER_PATCH_DOWNLOAD_URL_LABEL'); ?></th>
                <td><?php echo $this->item->developer_patch_download_url; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DEVELOPER_NAME_LABEL'); ?></th>
                <td><?php echo $this->item->developer_name; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DEVELOPER_CONTACT_EMAIL_LABEL'); ?></th>
                <td><?php echo $this->item->developer_contact_email; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_TRACKING_DB_NAME_LABEL'); ?></th>
                <td><?php echo $this->item->tracking_db_name; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_TRACKING_DB_ID_LABEL'); ?></th>
                <td><?php echo $this->item->tracking_db_id; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_JED_URL_LABEL'); ?></th>
                <td><?php echo $this->item->jed_url; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DEVELOPER_ADDITIONAL_INFO_LABEL'); ?></th>
                <td><?php echo nl2br($this->item->developer_additional_info); ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DOWNLOAD_URL_LABEL'); ?></th>
                <td><?php echo $this->item->download_url; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_CONSENT_TO_PROCESS_LABEL'); ?></th>
                <td><?php echo $this->item->consent_to_process; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_PASSED_TO_VEL_LABEL'); ?></th>
                <td><?php echo $this->item->passed_to_vel; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_VEL_ITEM_ID_LABEL'); ?></th>
                <td><?php echo $this->item->vel_item_id; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DATA_SOURCE_LABEL'); ?></th>
                <td><?php echo $this->item->data_source; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_DATE_SUBMITTED_LABEL'); ?></th>
                <td><?php echo $this->item->date_submitted; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_USER_IP_LABEL'); ?></th>
                <td><?php echo $this->item->user_ip; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_CREATED_BY_LABEL'); ?></th>
                <td><?php echo $this->item->created_by_name; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_MODIFIED_BY_LABEL'); ?></th>
                <td><?php echo $this->item->modified_by_name; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_CREATED_LABEL'); ?></th>
                <td><?php echo $this->item->created; ?></td>
            </tr>

            <tr>
                <th><?php echo Text::_('COM_JED_VEL_REPORT_FIELD_MODIFIED_LABEL'); ?></th>
                <td><?php echo $this->item->modified; ?></td>
            </tr>

        </table>

    </div>

<?php if ($canEdit): ?>

    <a class="btn"
       href="<?php echo Route::_('index.php?option=com_jed&task=velreport.edit&id=' . $this->item->id); ?>"><?php echo Text::_("COM_JED_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (Factory::getUser()->authorise('core.delete', 'com_jed.velreport.' . $this->item->id)) : ?>

    <a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo Text::_("COM_JED_DELETE_ITEM"); ?>
    </a>

    <div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal"
         aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3><?php echo Text::_('COM_JED_DELETE_ITEM'); ?></h3>
        </div>
        <div class="modal-body">
            <p><?php echo Text::sprintf('COM_JED_DELETE_CONFIRM', $this->item->id); ?></p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Close</button>
            <a href="<?php echo Route::_('index.php?option=com_jed&task=velreport.remove&id=' . $this->item->id, false, 2); ?>"
               class="btn btn-danger">
				<?php echo Text::_('COM_JED_DELETE_ITEM'); ?>
            </a>
        </div>
    </div>

<?php endif; ?>