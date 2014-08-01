<?php

namespace LarsMalach\LpFussballde\Controller;

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

/**
 * The main controller for the page backend module.
 *
 * @package LpFussballde
 * @author Lars Malach <Lars@Malach.de>
 */
class MainController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Initializes the controller before invoking an action method.
	 *
	 * @return void
	 */
	protected function initializeAction() {
		// Replace old pattern with new one (new pattern comes with Extbase 6.2)
		if (!preg_match('/\\\/', $this->viewObjectNamePattern)) {
			$this->viewObjectNamePattern = 'LarsMalach\@extension\View\@controller\@action@format';
		}
	}

	/**
	 * Show Action
	 *
	 * @return string
	 */
	public function showAction() {
		$contentObject = $this->configurationManager->getContentObject();

		$this->view->assignMultiple(
			array(
				'contentObject'				=> $contentObject,
				'extensionKey'				=> $this->request->getControllerExtensionKey(),
				'extensionKeyWithoutUnderl'	=> str_replace('_', '', $this->request->getControllerExtensionKey()),
				'pluginName'				=> $this->request->getPluginName(),
				'contentUid'				=> $contentObject->data['uid'],
			)
		);
	}
}