<?php
/**
 * @package     Redcore
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2012 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_REDCORE') or die;

/**
 * MySQLi database driver
 *
 * @package     Redcore
 * @subpackage  Database
 * @since       1.0
 */
class RDatabaseDriverMysqli extends JDatabaseDriverMysqli
{
	/**
	 * We can choose not to translate query with this variable
	 *
	 * @var  boolean
	 */
	public $translate = false;

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the string held is the
	 * <var>tablePrefix</var> class variable.
	 *
	 * @param   string  $sql     The SQL statement to prepare.
	 * @param   string  $prefix  The common table prefix.
	 *
	 * @return  string  The processed SQL statement.
	 *
	 * @since   11.1
	 */
	public function replacePrefix($sql, $prefix = '#__')
	{
		$literal = parent::replacePrefix($sql, $prefix);

		// Basic check for translations
		if (!$this->translate
			|| !stristr($sql, 'SELECT')
			|| JFactory::getLanguage()->getDefault() == JFactory::getLanguage()->getTag()
			|| JFactory::getApplication()->isAdmin())
		{
			return $literal;
		}

		$parsedSql = RTranslationHelper::parseSelectQuery($sql, $prefix);

		if (!empty($parsedSql))
		{
			return parent::replacePrefix($parsedSql, $prefix);
		}

		return $literal;
	}
}
