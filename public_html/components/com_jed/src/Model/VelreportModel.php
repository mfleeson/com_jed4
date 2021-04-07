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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Utilities\ArrayHelper;
use Jed\Component\Jed\Site\Helper\JedHelper;

/**
 * Velreport model.
 *
 * @since  1.6
 */
class VelreportModel extends ItemModel
{
	public $_item;


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
	 * This method revises if the $id of the item belongs to the current user
	 *
	 * @param   integer  $id  The id of the item
	 *
	 * @return  boolean             true if the user is the owner of the row, false if not.
	 *
	 */
	public function userIDItem($id)
	{
		try
		{
			$user = Factory::getUser();
			$db   = Factory::getDbo();

			$query = $db->getQuery(true);
			$query->select("id")
				->from($db->quoteName('#__jed_tickets'))
				->where("id = " . $db->escape($id))
				->where("created_by = " . $user->id);

			$db->setQuery($query);

			$results = $db->loadObject();
			if ($results)
			{
				return true;
			}
			else
			{
				return false;
			}
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
	 * @return void
	 *
	 * @since    1.6
	 *
	 * @throws Exception
	 */
	protected function populateState()
	{
		$app  = Factory::getApplication('com_jed');
		$user = Factory::getUser();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_jed')) && (!$user->authorise('core.edit', 'com_jed')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (Factory::getApplication()->input->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_jed.edit.velreport.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('id');
			Factory::getApplication()->setUserState('com_jed.edit.velreport.id', $id);
		}

		$this->setState('velreport.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('velreport.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer  $id  The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @throws Exception
	 */
	public function getItem($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('velreport.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				if (empty($result) || $this->isAdminOrSuperUser() || $table->created_by == Factory::getUser()->id)
				{

					// Check published state.
					if ($published = $this->getState('filter.published'))
					{
						if (isset($table->state) && $table->state != $published)
						{
							throw new \Exception(Text::_('COM_JED_ITEM_NOT_LOADED'), 403);
						}
					}

					// Convert the JTable to a clean JObject.
					$properties  = $table->getProperties(1);
					$this->_item = ArrayHelper::toObject($properties, 'JObject');

				}
				else
				{
					throw new Exception(Text::_("JERROR_ALERTNOAUTHOR"), 401);
				}
			}

			if (empty($this->_item))
			{
				throw new \Exception(Text::_('COM_JED_ITEM_NOT_LOADED'), 404);
			}
		}


		if (!JedHelper::is_blank($this->_item->pass_details_ok))
		{
			$this->_item->pass_details_ok = Text::_('COM_JED_VEL_REPORT_FIELD_PASS_DETAILS_OK_OPTION_' . $this->_item->pass_details_ok);
		}

		if (!JedHelper::is_blank($this->_item->vulnerability_type))
		{
			$this->_item->vulnerability_type = Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_TYPE_OPTION_' . $this->_item->vulnerability_type);
		}

		if (!JedHelper::is_blank($this->_item->exploit_type))
		{
			$this->_item->exploit_type = Text::_('COM_JED_VEL_REPORT_FIELD_EXPLOIT_TYPE_OPTION_' . $this->_item->exploit_type);
		}

		if (!JedHelper::is_blank($this->_item->vulnerability_actively_exploited))
		{
			$this->_item->vulnerability_actively_exploited = Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_ACTIVELY_EXPLOITED_OPTION_' . $this->_item->vulnerability_actively_exploited);
		}

		if (!JedHelper::is_blank($this->_item->vulnerability_publicly_available))
		{
			$this->_item->vulnerability_publicly_available = Text::_('COM_JED_VEL_REPORT_FIELD_VULNERABILITY_PUBLICLY_AVAILABLE_OPTION_' . $this->_item->vulnerability_publicly_available);
		}

		if (!JedHelper::is_blank($this->_item->developer_communication_type))
		{
			$this->_item->developer_communication_type = Text::_('COM_JED_VEL_REPORT_FIELD_DEVELOPER_COMMUNICATION_TYPE_OPTION_' . $this->_item->developer_communication_type);
		}

		if (!JedHelper::is_blank($this->_item->consent_to_process))
		{
			$this->_item->consent_to_process = Text::_('COM_JED_VEL_REPORT_FIELD_CONSENT_TO_PROCESS_OPTION_' . $this->_item->consent_to_process);
		}

		if (!JedHelper::is_blank($this->_item->passed_to_vel))
		{
			$this->_item->passed_to_vel = Text::_('COM_JED_VEL_REPORT_FIELD_PASSED_TO_VEL_OPTION_' . $this->_item->passed_to_vel);
		}

		if (!JedHelper::is_blank($this->_item->data_source))
		{
			$this->_item->data_source = Text::_('COM_JED_VEL_REPORT_FIELD_DATA_SOURCE_OPTION_' . $this->_item->data_source);
		}

		if (isset($this->_item->created_by))
		{
			$this->_item->created_by_name = Factory::getUser($this->_item->created_by)->name;
		}

		if (isset($this->_item->modified_by))
		{
			$this->_item->modified_by_name = Factory::getUser($this->_item->modified_by)->name;
		}

		return $this->_item;
	}

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string  $type    Name of the JTable class to get an instance of.
	 * @param   string  $prefix  Prefix for the table class name. Optional.
	 * @param   array   $config  Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Velreport', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string  $alias  Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();
		$result     = null;
		$aliasKey   = null;
		if (method_exists($this, 'getAliasFieldNameByView'))
		{
			$aliasKey = $this->getAliasFieldNameByView('velreport');
		}


		if (key_exists('alias', $properties))
		{
			$table->load(array('alias' => $alias));
			$result = $table->id;
		}
		elseif (isset($aliasKey) && key_exists($aliasKey, $properties))
		{
			$table->load(array($aliasKey => $alias));
			$result = $table->id;
		}
		if (empty($result) || $this->isAdminOrSuperUser() || $table->created_by == Factory::getUser()->id)
		{
			return $result;
		}
		else
		{
			throw new Exception(Text::_("JERROR_ALERTNOAUTHOR"), 401);
		}
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('velreport.id');
		if ($id || $this->userIDItem($id) || $this->isAdminOrSuperUser())
		{
			if ($id)
			{
				// Initialise the table
				$table = $this->getTable();

				// Attempt to check the row in.
				if (method_exists($table, 'checkin'))
				{
					if (!$table->checkin($id))
					{
						return false;
					}
				}
			}

			return true;
		}
		else
		{
			throw new Exception(Text::_("JERROR_ALERTNOAUTHOR"), 401);
		}
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('velreport.id');

		if ($id || $this->userIDItem($id) || $this->isAdminOrSuperUser())
		{
			if ($id)
			{
				// Initialise the table
				$table = $this->getTable();

				// Get the current user object.
				$user = Factory::getUser();

				// Attempt to check the row out.
				if (method_exists($table, 'checkout'))
				{
					if (!$table->checkout($user->get('id'), $id))
					{
						return false;
					}
				}
			}

			return true;
		}
		else
		{
			throw new Exception(Text::_("JERROR_ALERTNOAUTHOR"), 401);
		}
	}

	/**
	 * Publish the element
	 *
	 * @param   int  $id     Item id
	 * @param   int  $state  Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
		if ($id || $this->userIDItem($id) || $this->isAdminOrSuperUser())
		{
			$table->load($id);
			$table->state = $state;

			return $table->store();
		}
		else
		{
			throw new Exception(Text::_("JERROR_ALERTNOAUTHOR"), 401);
		}
	}

	/**
	 * Method to delete an item
	 *
	 * Commented out as Reports should not be deleted from front-end
	 *
	 * @param   int  $id  Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		/*$table = $this->getTable();

		if (empty($result) || $this->isAdminOrSuperUser() || $table->created_by == Factory::getUser()->id)
		{
			return $table->delete($id);
		}
		else
		{
			throw new Exception(Text::_("JERROR_ALERTNOAUTHOR"), 401);
		} */
	}


}
