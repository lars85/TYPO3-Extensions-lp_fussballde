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
 * Configuration Manager
 *
 * @package LpFussballde
 * @author Lars Malach <Lars@Malach.de>
 */
class Tx_LpFussballdeF4x_Service_ConfigurationManager implements t3lib_Singleton {

	/**
	 * @var Tx_Extbase_Configuration_ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * @return array
	 */
	public function getExtensionConfiguration() {
		$setup = $this->configurationManager->getConfiguration(
			Tx_Extbase_Configuration_ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		return !empty($setup['plugin.']['tx_lpfussballdef4x.']) ? $setup['plugin.']['tx_lpfussballdef4x.'] : array();
	}
}