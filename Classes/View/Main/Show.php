<?php

Namespace LarsPeipmann\LpFussballde\View\Main;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Lars Peipmann <Lars@Peipmann.de>
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
 * @package LpFussballde
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Show extends \TYPO3\CMS\Extbase\Mvc\View\AbstractView {
	/**
	 * Renders the view
	 *
	 * @return string
	 */
	public function render() {
		$fields = array();
		$this->mergeIntoOneArray($this->variables, $fields);

		/** @var $contentObject \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
		$contentObject = &$GLOBALS['TSFE']->cObj;
		$contentObject->start($fields);

		/** @var $typoScriptObject \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
		$typoScriptObject = &$GLOBALS['TSFE'];
		$extensionTypoScript = $typoScriptObject->tmpl->setup['plugin.']['tx_lpfussballde.'];

		$jsFileString = $contentObject->cObjGetSingle($extensionTypoScript['includeJs'], $extensionTypoScript['includeJs.']);
		$jsFiles = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode("\n", $jsFileString, TRUE);
		foreach ($jsFiles as $jsFile) {
			$typoScriptObject->getPageRenderer()->addJsFile($jsFile);
		}

		if (!empty($extensionTypoScript['renderObj'])) {
			$content = $contentObject->cObjGetSingle($extensionTypoScript['renderObj'], $extensionTypoScript['renderObj.']);
		} else {
			$content = 'Please inlcude TypoScript static files (setup.txt and constants.txt) of lp_fussballde_f4x extension.';
		}

		return $content;
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

?>