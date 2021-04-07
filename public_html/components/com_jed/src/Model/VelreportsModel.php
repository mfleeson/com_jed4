<?php
/**
 * @package       JED
 *
 * @subpackage    VEL
 *
 * @copyright     Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Jed\Component\Jed\Site\Model;
// No direct access.
defined('_JEXEC') or die;

use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Jed\Component\Jed\Site\Helper\JedHelper;

/**
 * Methods supporting a list of Jed records.
 *
 * @since  1.6
 */
class VelreportsModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since      1.6
	 * @see        JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'reporter_fullname', 'a.reporter_fullname',
				'reporter_email', 'a.reporter_email',
				'reporter_organisation', 'a.reporter_organisation',
				'pass_details_ok', 'a.pass_details_ok',
				'vulnerability_type', 'a.vulnerability_type',
				'vulnerable_item_name', 'a.vulnerable_item_name',
				'vulnerable_item_version', 'a.vulnerable_item_version',
				'exploit_type', 'a.exploit_type',
				'exploit_other_description', 'a.exploit_other_description',
				'vulnerability_description', 'a.vulnerability_description',
				'vulnerability_how_found', 'a.vulnerability_how_found',
				'vulnerability_actively_exploited', 'a.vulnerability_actively_exploited',
				'vulnerability_publicly_available', 'a.vulnerability_publicly_available',
				'vulnerability_publicly_url', 'a.vulnerability_publicly_url',
				'vulnerability_specific_impact', 'a.vulnerability_specific_impact',
				'developer_communication_type', 'a.developer_communication_type',
				'developer_patch_download_url', 'a.developer_patch_download_url',
				'developer_name', 'a.developer_name',
				'developer_contact_email', 'a.developer_contact_email',
				'tracking_db_name', 'a.tracking_db_name',
				'tracking_db_id', 'a.tracking_db_id',
				'jed_url', 'a.jed_url',
				'developer_additional_info', 'a.developer_additional_info',
				'download_url', 'a.download_url',
				'consent_to_process', 'a.consent_to_process',
				'passed_to_vel', 'a.passed_to_vel',
				'vel_item_id', 'a.vel_item_id',
				'data_source', 'a.data_source',
				'date_submitted', 'a.date_submitted',
				'user_ip', 'a.user_ip',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'created', 'a.created',
				'modified', 'a.modified',
			);
		}

		parent::__construct($config);
	}


	/**
	 * Checks whether or not a user is manager or super user
	 *
	 * @return bool
	 */
	public function isAdminOrSuperUser()
	{
		try
		{
			$user = Factory::getUser();

			return in_array("8", $user->groups) || in_array("7", $user->groups);
		}
		catch (Exception $exc)
		{
			return false;
		}
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @since    1.6
	 * @throws Exception
	 *
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication();


		// List state information.

		parent::populateState($ordering, $direction);

		$context = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $context);


		// Split context into component and optional section
		$parts = FieldsHelper::extract($context);

		if ($parts)
		{
			$this->setState('filter.component', $parts[0]);
			$this->setState('filter.section', $parts[1]);
		}
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);

		$query->from('`#__jed_vel_report` AS a');


		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		if (!$this->isAdminOrSuperUser())
		{
			$query->where("a.created_by = " . Factory::getUser()->get("id"));
		}


		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!JedHelper::is_blank($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.vulnerable_item_name LIKE ' . $search . ' )');
			}
		}


		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'DESC');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{

			if (!JedHelper::is_blank($item->pass_details_ok))
			{
				$item->pass_details_ok = Text::_('COM_JED_VEL_REPORT_FIELD_PASS_DETAILS_OK_OPTION_' . strtoupper($item->pass_details_ok));
			}
           			if (!JedHelper::is_blank($item->vulnerability_type))
			{
				$item->vulnerability_type = Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_TYPE_OPTION_' . strtoupper($item->vulnerability_type));
			}

			if (!JedHelper::is_blank($item->exploit_type))
			{
				$item->exploit_type = Text::_('COM_JED_VEL_REPORT_FIELD_EXPLOIT_TYPE_OPTION_' . strtoupper($item->exploit_type));
			}

			if (!JedHelper::is_blank($item->vulnerability_actively_exploited))
			{
				$item->vulnerability_actively_exploited = Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_ACTIVELY_EXPLOITED_OPTION_' . strtoupper($item->vulnerability_actively_exploited));
			}

			if (!JedHelper::is_blank($item->vulnerability_publicly_available))
			{
				$item->vulnerability_publicly_available = Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_PUBLICLY_AVAILABLE_OPTION_' . strtoupper($item->vulnerability_publicly_available));
			}

			if (!JedHelper::is_blank($item->developer_communication_type))
			{
				$item->developer_communication_type = Text::_('COM_JED_VEL_REPORT_FIELD_DEVELOPER_COMMUNICATION_TYPE_OPTION_' . strtoupper($item->developer_communication_type));
			}

			if (!JedHelper::is_blank($item->consent_to_process))
			{
				$item->consent_to_process = Text::_('COM_JED_VEL_REPORT_FIELD_CONSENT_TO_PROCESS_OPTION_' . strtoupper($item->consent_to_process));
			}

			if (!JedHelper::is_blank($item->passed_to_vel))
			{
				$item->passed_to_vel = Text::_('COM_JED_VEL_REPORT_FIELD_PASSED_TO_VEL_OPTION_' . strtoupper($item->passed_to_vel));
			}
            else
            {
                echo "<h1>";print_r($item->passed_to_vel);echo "</h1>";
            }

			if (!JedHelper::is_blank($item->data_source))
			{
				$item->data_source = Text::_('COM_JED_VEL_REPORT_FIELD_DATA_SOURCE_OPTION_' . strtoupper($item->data_source));
			}
		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(Text::_("COM_JED_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);

		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}
