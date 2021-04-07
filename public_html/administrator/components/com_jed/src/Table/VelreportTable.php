<?php
/**
 * @package       JED
 *
 * @subpackage    VEL
 *
 * @copyright     Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Jed\Component\Jed\Administrator\Table;
// No direct access
defined('_JEXEC') or die;

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table as Table;
use \Joomla\CMS\Versioning\VersionableTableInterface;
use \Joomla\Database\DatabaseDriver;
use \Joomla\CMS\Filter\OutputFilter;
use \Joomla\CMS\Filesystem\File;




/**
 * Velreport table
 *
 * @since  1.5
 */
class VelreportTable extends Table implements VersionableTableInterface
{
	
	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_jed.velreport';
		parent::__construct('#__jed_vel_report', 'id', $db);
		$this->setColumnAlias('published', 'state');
		
	}

    /**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   4.0.0
	 */
	public function getTypeAlias()
	{
		return 'com_jed.velreport';
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  Optional array or list of parameters to ignore
	 *
	 * @return  null|string  null is operation was satisfactory, otherwise returns an error
	 *
	 * @see     JTable:bind
	 * @since   1.5
     * @throws Exception
	 */
	public function bind($array, $ignore = '')
	{
	    $date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');
	    

		// Support for multiple field: pass_details_ok
		if (isset($array['pass_details_ok']))
		{
			if (is_array($array['pass_details_ok']))
			{
				$array['pass_details_ok'] = implode(',',$array['pass_details_ok']);
			}
			elseif (strpos($array['pass_details_ok'], ',') != false)
			{
				$array['pass_details_ok'] = explode(',',$array['pass_details_ok']);
			}
			elseif (strlen($array['pass_details_ok']) == 0)
			{
				$array['pass_details_ok'] = '';
			}
		}
		else
		{
			$array['pass_details_ok'] = '';
		}

		// Support for multiple field: vulnerability_type
		if (isset($array['vulnerability_type']))
		{
			if (is_array($array['vulnerability_type']))
			{
				$array['vulnerability_type'] = implode(',',$array['vulnerability_type']);
			}
			elseif (strpos($array['vulnerability_type'], ',') != false)
			{
				$array['vulnerability_type'] = explode(',',$array['vulnerability_type']);
			}
			elseif (strlen($array['vulnerability_type']) == 0)
			{
				$array['vulnerability_type'] = '';
			}
		}
		else
		{
			$array['vulnerability_type'] = '';
		}

		// Support for multiple field: exploit_type
		if (isset($array['exploit_type']))
		{
			if (is_array($array['exploit_type']))
			{
				$array['exploit_type'] = implode(',',$array['exploit_type']);
			}
			elseif (strpos($array['exploit_type'], ',') != false)
			{
				$array['exploit_type'] = explode(',',$array['exploit_type']);
			}
			elseif (strlen($array['exploit_type']) == 0)
			{
				$array['exploit_type'] = '';
			}
		}
		else
		{
			$array['exploit_type'] = '';
		}

		// Support for multiple field: vulnerability_actively_exploited
		if (isset($array['vulnerability_actively_exploited']))
		{
			if (is_array($array['vulnerability_actively_exploited']))
			{
				$array['vulnerability_actively_exploited'] = implode(',',$array['vulnerability_actively_exploited']);
			}
			elseif (strpos($array['vulnerability_actively_exploited'], ',') != false)
			{
				$array['vulnerability_actively_exploited'] = explode(',',$array['vulnerability_actively_exploited']);
			}
			elseif (strlen($array['vulnerability_actively_exploited']) == 0)
			{
				$array['vulnerability_actively_exploited'] = '';
			}
		}
		else
		{
			$array['vulnerability_actively_exploited'] = '';
		}

		// Support for multiple field: vulnerability_publicly_available
		if (isset($array['vulnerability_publicly_available']))
		{
			if (is_array($array['vulnerability_publicly_available']))
			{
				$array['vulnerability_publicly_available'] = implode(',',$array['vulnerability_publicly_available']);
			}
			elseif (strpos($array['vulnerability_publicly_available'], ',') != false)
			{
				$array['vulnerability_publicly_available'] = explode(',',$array['vulnerability_publicly_available']);
			}
			elseif (strlen($array['vulnerability_publicly_available']) == 0)
			{
				$array['vulnerability_publicly_available'] = '';
			}
		}
		else
		{
			$array['vulnerability_publicly_available'] = '';
		}

		// Support for multiple field: developer_communication_type
		if (isset($array['developer_communication_type']))
		{
			if (is_array($array['developer_communication_type']))
			{
				$array['developer_communication_type'] = implode(',',$array['developer_communication_type']);
			}
			elseif (strpos($array['developer_communication_type'], ',') != false)
			{
				$array['developer_communication_type'] = explode(',',$array['developer_communication_type']);
			}
			elseif (strlen($array['developer_communication_type']) == 0)
			{
				$array['developer_communication_type'] = '';
			}
		}
		else
		{
			$array['developer_communication_type'] = '';
		}

		// Support for multiple field: consent_to_process
		if (isset($array['consent_to_process']))
		{
			if (is_array($array['consent_to_process']))
			{
				$array['consent_to_process'] = implode(',',$array['consent_to_process']);
			}
			elseif (strpos($array['consent_to_process'], ',') != false)
			{
				$array['consent_to_process'] = explode(',',$array['consent_to_process']);
			}
			elseif (strlen($array['consent_to_process']) == 0)
			{
				$array['consent_to_process'] = '';
			}
		}
		else
		{
			$array['consent_to_process'] = '';
		}

		// Support for multiple field: passed_to_vel
		if (isset($array['passed_to_vel']))
		{
			if (is_array($array['passed_to_vel']))
			{
				$array['passed_to_vel'] = implode(',',$array['passed_to_vel']);
			}
			elseif (strpos($array['passed_to_vel'], ',') != false)
			{
				$array['passed_to_vel'] = explode(',',$array['passed_to_vel']);
			}
			elseif (strlen($array['passed_to_vel']) == 0)
			{
				$array['passed_to_vel'] = '';
			}
		}
		else
		{
			$array['passed_to_vel'] = '';
		}

		// Support for multiple field: data_source
		if (isset($array['data_source']))
		{
			if (is_array($array['data_source']))
			{
				$array['data_source'] = implode(',',$array['data_source']);
			}
			elseif (strpos($array['data_source'], ',') != false)
			{
				$array['data_source'] = explode(',',$array['data_source']);
			}
			elseif (strlen($array['data_source']) == 0)
			{
				$array['data_source'] = '';
			}
		}
		else
		{
			$array['data_source'] = '';
		}

		// Support for empty date field: date_submitted
		if($array['date_submitted'] == '0000-00-00' )
		{
			$array['date_submitted'] = '';
		}

		if ($array['id'] == 0 && empty($array['created_by']))
		{
			$array['created_by'] = Factory::getUser()->id;
		}

		if ($array['id'] == 0 && empty($array['modified_by']))
		{
			$array['modified_by'] = Factory::getUser()->id;
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified_by'] = Factory::getUser()->id;
		}

		if ($array['id'] == 0)
		{
			$array['created'] = $date->toSql();
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified'] = $date->toSql();
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!Factory::getUser()->authorise('core.admin', 'com_jed.velreport.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_jed/access.xml',
				"/access/section[@name='velreport']/"
			);
			$default_actions = Access::getAssetRules('com_jed.velreport.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
                if (key_exists($action->name, $default_actions))
                {
                    $array_jaccess[$action->name] = $default_actions[$action->name];
                }
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 *
	 * @param   array  $jaccessrules  An array of JAccessRule objects.
	 *
	 * @return  array
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}

	/**
	 * Overloaded check function
	 *
	 * @return bool
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}
		
		

		return parent::check();
	}

	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 *
	 * @return string The asset name
	 *
	 * @see Table::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_jed.velreport.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   JTable   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see Table::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
	 */
	protected function _getAssetParentId($table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = Table::getInstance('Asset');

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName('com_jed');

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	//XXX_CUSTOM_TABLE_FUNCTION

	
    /**
     * Delete a record by id
     *
     * @param   mixed  $pk  Primary key value to delete. Optional
     *
     * @return bool
     */
    public function delete($pk = null)
    {
        $this->load($pk);
        $result = parent::delete($pk);
        
        return $result;
    }
}
