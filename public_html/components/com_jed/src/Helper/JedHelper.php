<?php
/**
 * @package    JED
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Jed\Component\Jed\Site\Helper;

defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

/**
 * JED Helper
 * 
 * @since     4.0.0
 * @package   JED
 */
class JedHelper
{
	/**
	 * Function to format JED Extension Images
	 * A lot of the JED 3 data has extra spaces and messyness in the restored data. This fixes that display.
	 *
	 * @param   string  $filename  The image filename
	 * @param   string  $size      Size of image, small|large
	 *
	 * @return  string  Full image url
	 *
	 * @since   4.0.0
	 */
	static public function formatImage($filename, $size = 'small'): string
	{
		if (!$filename)
		{
			return '';
		}

		// Filename for small image
		if ($size === 'small')
		{
			$imageSize = str_replace('.', '_resizeDown400px175px16.', $filename);
		}

		// Filename for large image
		if ($size === 'large')
		{
			$imageSize = str_replace('.', '_resizeDown1200px525px16.', $filename);
		}

		// Use CDN url
		return 'https://extensionscdn.joomla.org/cache/fab_image/' . $imageSize;
	}

	/**
	 * reformatTitle
	 *
	 * A lot of the restored JED 3 titles have extra spacing or missing punctuation. This fixes that for display.
	 *
	 * @param $l_str
	 *
	 * @return string
	 *
	 * @since  1.0
	 */
	public static function reformatTitle($l_str): string
	{

		$loc = str_replace(',', ', ', $l_str);
		$loc = str_replace(' ,', ',', $loc);
		$loc = str_replace('  ', ' ', $loc);

		return trim($loc);
	}

	public static function IsLoggedIn()
	{

		$user = Factory::getUser();
		if ($user->id > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Gets the edit permission for an user
	 *
	 * @param   mixed  $item  The item
	 *
	 * @return  bool
	 *
	 * @since   1.0
	 */
	public static function canUserEdit($item)
	{
		$permission = false;
		$user       = Factory::getUser();

		if ($user->authorise('core.edit', 'com_jed_new'))
		{
			$permission = true;
		}
		else
		{
			if (isset($item->created_by))
			{
				if ($user->authorise('core.edit.own', 'com_jed_new') && $item->created_by == $user->id)
				{
					$permission = true;
				}
			}
			else
			{
				$permission = true;
			}
		}

		return $permission;
	}

	/**
	 * Returns URL for user login
	 *
	 * @return string
	 *
	 * @since  1.0
	 */
	public static function getLoginlink(): string
	{
		$redirectUrl    = '&return=' . urlencode(base64_encode(Uri::getInstance()->toString()));
		$joomlaLoginUrl = 'index.php?option=com_users&view=login';

		return $joomlaLoginUrl . $redirectUrl;
	}


	/**
	 * When a VEL is reported or a Developer Update made this creates a corresponding Ticket
	 *
	 * @param   int  $report_type  1 for VEL REPORT, 2 for DEVELOPER UPDATE
	 * @param   int  $item_id      Reference for stored report
	 *
	 * @return  bool
	 */
	public function CreateVELReportTicket($report_type, $item_id)
	{
		//  $ticketInstance =  $this->getModel( 'Ticket' );
		$db     = Factory::getDbo();
		$query  = $db->getQuery(true);
		$ticket = array();
		$user   = Factory::getUser();

		$ticket['id']          = 0;
		$ticket['created_by']  = $user->id;
		$ticket['modified_by'] = $user->id;
		$ticket['created_on']  = 'now()';
		$ticket['modified_on'] = 'now()';

		$ticket['ticket_origin'] = 0; //Registered User

		switch ($report_type)
		{
			case 1: // VEL REPORT
				$ticket['ticket_category_type'] = 10;
				$ticket['ticket_subject']       = "A new Vulnerable Item Report";
				$ticket['linked_item_type']     = 3;     //    Vulnerable Item Initial Report
				break;
			case 2: // DEVELOPER UPDATE
				$ticket['ticket_category_type'] = 11;
				$ticket['ticket_subject']       = "A new Developer Update";
				$ticket['linked_item_type']     = 4;     //    Vulnerable Item Developer Update
				break;

		}

		/*   <option value="1">Extension</option>
		   <option value="0">Unknown</option>
		   <option value="2">Review</option>
		   <option value="3">Joomla Site Issue</option>
		   <option value="4">New Listing Support</option>
		   <option value="5">Current Listing Support</option>
		   <option value="6">Site Technical Issues</option>
		   <option value="7">Unpublished Support</option>
		   <option value="8">Reported Review</option>
		   <option value="9">Reported Extension</option>
		   <option value="10">Vulnerable Item Report</option>
		   <option value="11">VEL Developer Update</option>   */


		$ticket['allocated_group'] = 0; //Anyone


		/* <option value="0" selected="selected">Unknown</option>
		 <option value="1">Extension</option>
		 <option value="2">Review</option>
		 <option value="3">Vulnerable Item Initial Report</option>
		 <option value="4">Vulnerable Item Developer Update</option>
		 <option value="5">Vulnerable Item Email Correspondence</option> */
		$ticket['linked_item_id'] = $item_id;
		$ticket['ticket_status']  = 0; //New
		/*
			<option value="0" selected="selected">New</option>
			<option value="1">Awaiting User</option>
			<option value="2">Awaiting JED</option>
			<option value="3">Resolved</option>
			<option value="4">Closed</option>
			<option value="5">Updated</option>

		*/
		$ticket['ticket_text']    = 'Please see linked report';
		$ticket['internal_notes'] = '';

		$ticket['uploaded_files_preview']  = '';
		$ticket['uploaded_files_location'] = '';
		$ticket['allocated_to']            = 0;
		$ticket['parent_id']               = -1;
		$ticket['state']                   = 0;
		$ticket['ordering']                = 0;
		$ticket['checked_out']             = 0;
		$ticket['checked_out_time']        = '0000-00-00 00:00:00';

		foreach ($ticket as $k => $v)
		{
			// print_r($v);echo "\n";
			//    echo "{$k} => {$v}";
			$columns[] = $k;
			if (str_ends_with($k, "_on"))
			{

				$values[] = $v;
			}
			else
			{
				$values[] = $db->quote($v);
			}

		}


		$query->insert($db->qn('#__jed_tickets'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));

		//$db->setQuery($query);

		//   $db->execute();
		/* rewrite to grab next row and check it out! */
		//    $lastRowId = $db->insertid();
		//    print_r($lastRowId);

		return $lastRowId;


	}

	/**
	 * is_blank
	 *
	 * isEmpty sees a value of 0 has being empty which means that using it to test database option values fails with entries of 0
	 *
	 * @param $value
	 *
	 * @return bool
	 *
	 * @since  1.0
	 */
	public function is_blank($value)
	{
		return empty($value) && !is_numeric($value);
	}

}
