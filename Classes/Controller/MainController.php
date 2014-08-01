<?php

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
 * The main controller.
 *
 * @package LpFussballde
 * @author Lars Malach <Lars@Malach.de>
 */
class Tx_LpFussballdeF4x_Controller_MainController extends Tx_Extbase_MVC_Controller_ActionController {

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
