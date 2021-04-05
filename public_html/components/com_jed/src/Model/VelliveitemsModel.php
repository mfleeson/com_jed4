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

use Jed\Component\Jed\Site\Helper\JedHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Methods supporting a list of Jed records.
 *
 * @since  1.6
 */
class VelliveitemsModel extends ListModel
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
				'title', 'a.title',
				'publication_date_sort', 'a.publication_date_sort'
			);
		}

		parent::__construct($config);
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
	 * @throws \Exception
	 *
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('publication_date_sort', 'DESC');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{

		$db    = $this->getDbo();
		$query = $db->getQuery(true);


		$query->select("id,`title` AS `title`, `alias` AS `alias`, `state` AS `published`, IF((`modified` > `created`),DATE_FORMAT(`modified`,'%d %M %Y'),DATE_FORMAT(`created`,'%d %M %Y')) AS `publication_date`,IF((`modified` > `created`),`modified`,`created`) AS `publication_date_sort`");

		$query->from($db->qn('#__jed_vel_vulnerable_item', 'a'));

		$query->where('a.status = 1 AND a.state=1'); //Status 0 = reported, 1 = live, 2 = patched
		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.title LIKE ' . $search . ' )');
			}
		}
		$query->order($db->escape($this->getState('list.ordering', 'publication_date_sort')) . ' ' .
			$db->escape($this->getState('list.direction', 'DESC')));

		//echo($query->__toString());//exit();

		return $query;

	}

	/**
	 * Method to get an array of data items
	 *
	 * @return array|bool An array of data on success, false on failure.
	 *
	 * @since 1.0
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $nr => &$item)
		{
			// Always create a slug for sef URL's
			//	$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id.':'.$item->alias : $item->id;
			$item->title              = JedHelper::reformatTitle($item->title);
			$item->public_description = JedHelper::reformatTitle($item->public_description);
			// Always create a slug for sef URL's
			$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id . ':' . $item->alias : $item->id;
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
	 * @since 1.0
	 *
	 */
	private function isValidDate(string $date): bool
	{
		$date = str_replace('/', '-', $date);

		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : false;
	}


}
