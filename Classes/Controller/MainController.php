<?php

namespace TYPO3\LarspFussballdeJs\Controller;

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
 * @package LarspFussballJs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class MainController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	protected $requiredProperties = array('key', 'competitionId', 'season', 'display');

	/**
	 * @var \TYPO3\CMS\Extbase\Service\FlexFormService
	 */
	protected $flexFormService;

	/**
	 * @param \TYPO3\CMS\Extbase\Service\FlexFormService $flexFormService
	 * @return void
	 */
	public function injectFlexFormService(\TYPO3\CMS\Extbase\Service\FlexFormService $flexFormService) {
		$this->flexFormService = $flexFormService;
	}

	/**
	 * @return string
	 */
	public function showAction() {
		/** @var $contentObject \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
		$contentObject = $this->configurationManager->getContentObject();
		/** @var $typoScriptObject \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
		$typoScriptObject = &$GLOBALS['TSFE'];

		if (empty($typoScriptObject->tmpl->setup['plugin.']['tx_larspfussballdejs_pi1.'])) {
			$this->flashMessageContainer->add(
				'Missing configuration: plugin.tx_larspfussballdejs_pi1',
				'Fussball.de JavaScript',
				\TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
			);
			return $this->view->render();
		}
		$extensionTypoScript = $typoScriptObject->tmpl->setup['plugin.']['tx_larspfussballdejs_pi1.'];

		$properties = $this->getProperties($extensionTypoScript, $contentObject);
		$missingProperties = $this->getMissingProperties($properties);
		if (count($missingProperties)) {
			$this->flashMessageContainer->add(
				'Missing properties: ' . join(', ', $missingProperties),
				'Fussball.de JavaScript',
				\TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
			);
			return $this->view->render();
		}
		$this->view->assign('properties', $properties);
		$this->view->assign('missingProperties', $missingProperties);

		$jsFiles = $this->getJavaScriptFiles($extensionTypoScript, $contentObject);
		foreach ($jsFiles as $jsFile) {
			$typoScriptObject->getPageRenderer()->addJsFile($jsFile);
		}
	}

	protected function getMissingProperties($properties) {
		$missing = array();
		foreach ($this->requiredProperties as $key) {
			if (!isset($properties[$key]) || strlen($properties[$key]) == 0) {
				$missing[] = $key;
			}
		}
		return $missing;
	}

	protected function getProperties($extensionTypoScript, $contentObject) {
		$typoScriptProperties = $this->getTypoScriptProperties($contentObject, $extensionTypoScript);
		$flexFormProperties = $this->flexFormService->convertFlexFormContentToArray($contentObject->data['pi_flexform']);
		$flexFormProperties = $this->patchFlexFormProperties($flexFormProperties, array_keys($typoScriptProperties));

		if ($flexFormProperties['display'] === 'default') {
			unset($flexFormProperties['display']);
		}
		$properties = \TYPO3\CMS\Core\Utility\GeneralUtility::array_merge_recursive_overrule($typoScriptProperties, $flexFormProperties, FALSE, FALSE);
		$properties['extKey'] = 'larsp_fussballde_js';
		$properties['contentId'] = $contentObject->data['uid'];

		if (isset($properties['season']) && $properties['season'] == 'current') {
			$seasonTs = $extensionTypoScript['properties.']['season.'];
			$year = date('y');
			if (time() < mktime(0, 0, 0, intval($seasonTs['swapMonth']), intval($seasonTs['swapDay']))) {
				$properties['season'] = str_pad(intval($year) - 1, 2, '0', STR_PAD_LEFT) . str_pad($year, 2, '0', STR_PAD_LEFT);
			} else {
				$properties['season'] = str_pad($year, 2, '0', STR_PAD_LEFT) . str_pad(intval($year) + 1, 2, '0', STR_PAD_LEFT);
			}
		}

		foreach ($properties as $key => $value) {
			$properties[$key] = htmlspecialchars($value);
		}

		return $properties;
	}

	protected function getJavaScriptFiles($extensionTypoScript, $contentObject) {
		$configuration = $extensionTypoScript['includeJs.'];
		$files = array();
		foreach ($configuration as $key => $value) {
			$keyWithoutDot = str_replace('.', '', $key);
			if (isset($files[$keyWithoutDot])) {
				continue;
			}
			$file = $this->stdWrap($configuration, $keyWithoutDot, $contentObject);
			if ($file !== NULL) {
				$files[$keyWithoutDot] = $file;
			}
		}
		return $files;
	}
	
	protected function getTypoScriptProperties($contentObject, $extensionTypoScript) {
		$properties = array();

		$typoScriptKeysLowerCase = array();
		foreach (array_keys($extensionTypoScript) as $key) {
			$typoScriptKeysLowerCase[strtolower($key)] = $key;
		}

		foreach ($extensionTypoScript['properties.'] as $key => $value) {
			$keyWithoutDot = str_replace('.', '', $key);
			if (isset($properties[$keyWithoutDot])) {
				continue;
			}

			$propertyValue = $this->stdWrap($extensionTypoScript['properties.'], $keyWithoutDot, $contentObject);

			if (strlen($propertyValue) == 0 && isset($typoScriptKeysLowerCase[strtolower($keyWithoutDot)])) {
				$propertyValue = $extensionTypoScript[ $typoScriptKeysLowerCase[strtolower($keyWithoutDot)] ];
			}

			if ($propertyValue !== NULL) {
				$properties[$keyWithoutDot] = $propertyValue;
			}
		}

		return $properties;
	}

	protected function patchFlexFormProperties($flexFormProperties, $correctPropertyKeys) {
		$newProperties = array();

		$correctPropertyKeysLowerCase = array();
		foreach ($correctPropertyKeys as $key) {
			$correctPropertyKeysLowerCase[strtolower($key)] = $key;
		}

		foreach ($flexFormProperties as $key => $value) {
			if (is_array($value)) {
				continue;
			}
			if (isset($correctPropertyKeysLowerCase[strtolower($key)])) {
				$newProperties[ $correctPropertyKeysLowerCase[strtolower($key)] ] = $value;
			} else {
				$newProperties[ $key ] = $value;
			}
		}

		return $newProperties;
	}

	/**
	 * Processes a stdWrap.
	 *
	 * @param array $configuration
	 * @param string $key
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject
	 * @return string
	 */
	protected function stdWrap($configuration, $key, $contentObject) {
		$value = NULL;
		if (isset($configuration[$key]) && isset($configuration[$key . '.'])) {
			$value = $contentObject->stdWrap($configuration[$key], $configuration[$key . '.']);
		} elseif (isset($configuration[$key])) {
			$value = $configuration[$key];
		} elseif (isset($configuration[$key . '.'])) {
			$value = $contentObject->stdWrap('', $configuration[$key . '.']);
		}
		return $value;
	}
}