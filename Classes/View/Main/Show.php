<?php

//namespace TYPO3\LarspFussballdeJs\View\Main;

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
 * @package LarspFussballJs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

//class show extends \TYPO3\CMS\Extbase\Mvc\View\AbstractView {
class Tx_LarspFussballdeJs_View_Main_Show extends \TYPO3\CMS\Extbase\Mvc\View\AbstractView {
	/**
	 * Renders the view
	 *
	 * @return string
	 */
	public function render() {
		$content = '';

		/** @var $flashMessages \TYPO3\CMS\Core\Messaging\FlashMessage[] */
		$flashMessages = $this->controllerContext->getFlashMessageContainer()->getAllMessagesAndFlush();
		if (count($flashMessages)) {
			foreach ($flashMessages as $flashMessage) {
				$content .= $flashMessage->render();
				if ($flashMessage->getSeverity() == \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR) {
					return $content;
				}
			}
		}

		/** @var $contentObject \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
		$contentObject = &$GLOBALS['TSFE']->cObj;
		/** @var $typoScriptObject \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
		$typoScriptObject = &$GLOBALS['TSFE'];
		$extensionTypoScript = $typoScriptObject->tmpl->setup['plugin.']['tx_larspfussballdejs_pi1.'];

		array_push($typoScriptObject->registerStack, $typoScriptObject->register);
		$this->addArrayToRegister($typoScriptObject, $this->variables['properties']);

		$content .= $contentObject->cObjGetSingle($extensionTypoScript['renderObj'], $extensionTypoScript['renderObj.']);

		$typoScriptObject->register = array_pop($typoScriptObject->registerStack);

		return '<div class="tx-larspfussballdejs-pi1">' . $content . '</div>';
	}

	protected function addArrayToRegister($typoScriptObject, $array) {
		foreach ($array as $key => $value) {
			$typoScriptObject->register[$key] = $value;
		}
		return $this;
	}
}