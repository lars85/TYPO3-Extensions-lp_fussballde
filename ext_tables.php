<?php
defined('TYPO3_MODE') or die();

$boot = function($packageKey) {
	$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($packageKey);

	/**
	 * Add setup.txt / constants.txt to static files selection in template records
	 */
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
		$packageKey,
		'Configuration/TypoScript',
		'Fussball.de'
	);

	/**
	 * Add Plugin
	 */
	$pluginName = 'pi1';
	$pluginSignatureList = strtolower($extensionName) . '_' . $pluginName;

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		$packageKey,
		$pluginName,
		'LLL:EXT:' . $packageKey . '/Resources/Private/Language/locallang_flexform.xlf:' . $pluginName,
		'EXT:' . $packageKey . '/ext_icon.gif'
	);

	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureList] = 'layout,select_key,pages,recursive';
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureList] = 'pi_flexform';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
		$pluginSignatureList,
		'FILE:EXT:' . $packageKey . '/Configuration/FlexForms/flexform_' . $pluginName . '.xml'
	);
};

$boot($_EXTKEY);
unset($boot);