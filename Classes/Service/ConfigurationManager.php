<?php

namespace LarsMalach\LpFussballde\Service;

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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

/**
 * Configuration Manager
 *
 * @package LpFussballde
 * @author Lars Malach <Lars@Malach.de>
 */
class ConfigurationManager implements SingletonInterface {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Returns extension configuration
	 *
	 * @return array
	 * @throws InvalidConfigurationTypeException
	 */
	public function getExtensionConfiguration() {
		$setup = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		return !empty($setup['plugin.']['tx_lpfussballde.']) ? $setup['plugin.']['tx_lpfussballde.'] : array();
	}
}