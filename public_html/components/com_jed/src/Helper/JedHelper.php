<?php
/**
 * @package    JED
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

namespace Jed\Component\Jed\Site\Helper;

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
}
