<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(function () {
    ExtensionManagementUtility::addTypoScriptSetup('
module.tx_form.settings.yamlConfigurations.1998 = EXT:friendlycaptcha/Configuration/Yaml/FormSetup.yaml
    ');
});
