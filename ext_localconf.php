<?php
defined('TYPO3_MODE') or die();

$boot = function($packageKey) {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'LarsMalach.' . $packageKey,
		'pi1',
		array(
			'Main' => 'show',
		)
	);
};

$boot($_EXTKEY);
unset($boot);