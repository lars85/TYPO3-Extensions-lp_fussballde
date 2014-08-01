<?php

Namespace LarsPeipmann\LpFussballde\View\Main;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Mvc\View\AbstractView;

/**
 * The view
 *
 * @package LpFussballde
 * @author Lars Malach <Lars@Malach.de>
 */
class Show extends AbstractView {

	/**
	 * @var \LarsPeipmann\LpFussballde\Service\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Renders the view
	 *
	 * @return string
	 */
	public function render() {
		/** @var $contentObject \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
		$contentObject = $this->variables['contentObject'];
		unset($this->variables['contentObject']);

		$fields = array();
		$this->mergeIntoOneArray($this->variables, $fields);
		$contentObject->start($fields);

		$extensionTypoScript = $this->configurationManager->getExtensionConfiguration();

		$jsFileString = $contentObject->cObjGetSingle($extensionTypoScript['includeJs'], $extensionTypoScript['includeJs.']);
		$jsFiles = GeneralUtility::trimExplode("\n", $jsFileString, TRUE);
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
	 * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
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