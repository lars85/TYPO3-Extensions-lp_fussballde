<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Lars Peipmann <Lars@Peipmann.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The main controller for the page backend module.
 *
 * @package LpFussballdeF4x
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_LpFussballdeF4x_View_Main_Show extends Tx_Extbase_MVC_View_AbstractView {

	/**
	 * @var Tx_LpFussballdeF4x_Service_ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @param Tx_LpFussballdeF4x_Service_ConfigurationManager $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(Tx_LpFussballdeF4x_Service_ConfigurationManager $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * Renders the view
	 *
	 * @return string
	 */
	public function render() {
		/** @var $contentObject tslib_cObj */
		$contentObject = $this->variables['contentObject'];
		unset($this->variables['contentObject']);

		$fields = array();
		$this->mergeIntoOneArray($this->variables, $fields);
		$contentObject->start($fields);

		$extensionTypoScript = $this->configurationManager->getExtensionConfiguration();

		$jsFileString = $contentObject->cObjGetSingle($extensionTypoScript['includeJs'], $extensionTypoScript['includeJs.']);
		$jsFiles = t3lib_div::trimExplode("\n", $jsFileString, TRUE);
		foreach ($jsFiles as $jsFile) {
			$this->getTypoScriptFrontendController()->getPageRenderer()->addJsFile($jsFile);
		}

		if (!empty($extensionTypoScript['renderObj'])) {
			$content = $contentObject->cObjGetSingle($extensionTypoScript['renderObj'], $extensionTypoScript['renderObj.']);
		} else {
			$content = 'Please inlcude TypoScript static files (setup.txt and constants.txt) of lp_fussballde_f4x extension.';
		}

		return $content;
	}

	/**
	 * Returns the TypoScript Frontend Controller
	 *
	 * @return tslib_fe
	 */
	protected function getTypoScriptFrontendController() {
		return $GLOBALS['TSFE'];
	}

	/**
	 * Merge nested array into one array.
	 *
	 * @param array $variables
	 * @param array $fields
	 * @return void
	 */
	protected function mergeIntoOneArray($variables, &$fields) {
		foreach ($variables as $key => $value) {
			if (is_array($value)) {
				$this->mergeIntoOneArray($value, $fields);
			} else {
				if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
					$fields[$key] = htmlspecialchars($value, ENT_HTML5);
				} else {
					$fields[$key] = htmlspecialchars($value);
				}
			}
		}
	}
}
