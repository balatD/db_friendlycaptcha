<?php

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'friendlycaptcha',
    'Configuration/TypoScript/',
    'FriendlyCaptcha'
);
