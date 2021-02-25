<?php
/**
 * @package    JED
 *
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Jed\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

/**
 * Email logs table class
 *
 * @since  4.0.0
 */
class EmaillogTable extends Table
{
	/**
	 * Indicates that columns fully support the NULL value in the database
	 *
	 * @var    boolean
	 * @since  4.0.0
	 */
	protected $_supportNullValue = true;

	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  A database connector object
	 * @since   4.0.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__jed_email_logs', 'id', $db);
	}
}
