<?php

/**
 * Fabrik PivotTable Viz Model
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.visualization.pivottable
 * @copyright   Copyright (C) 2019 Projeto PITT. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

jimport('joomla.application.component.model');

require_once JPATH_SITE . '/components/com_fabrik/models/visualization.php';

/**
 * Renders pivottable visualization
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.visualization.pivottable
 * @since       3.0
 */
class FabrikModelPivotTable extends FabrikFEModelVisualization
{
	public function getJSData()
	{
		$params = $this->getParams();
		$listid = $params->get('pivottable_table', array());

		/**
		 * Get application context to build the url for download the list as a csv file
		 * */
		$urlcsv = JUri::base();
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		$query->select('db_table_name');
		$query->from($db->quoteName('#__fabrik_lists'));
		$query->where($db->quoteName('id') . ' = ' . $listid);
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObjectList();

		$csvfile = $urlcsv . "tmp/". $results[0]->db_table_name . "-export.csv";

		return json_encode($csvfile);
	}

	public function getListId()
	{
		$params = $this->getParams();

		$listId = $params->get('pivottable_table', array());

		return $listId;
	}

	public function getNameList()
	{
		$listid	= $this->getListId();
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('db_table_name');
		$query->from($db->quoteName('#__fabrik_lists'));
		$query->where($db->quoteName('id') . ' = ' . $listid);
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObjectList();

		return str_replace("_", "-", $results[0]->db_table_name);
	}

	public function getUrlBase()
	{
		$url = JUri::base();
		return $url;
	}

	public function getUrlContext()
	{
		/**
		 * Get application context to build the url for download the list as a csv file
		 * */
		$urlcsv = JUri::base();
		$params = $this->getParams();
		$listid = $params->get('pivottable_table');
		$urlcsv .= 'administrator/index.php?option=com_fabrik&view=list&listid=' . $listid . '&format=csv&download=1';

		return $urlcsv;
	}
}
