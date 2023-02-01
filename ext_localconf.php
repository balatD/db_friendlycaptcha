<?php

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:form/Resources/Private/Language/Database.xlf'][] =
        'EXT:db_friendlycaptcha/Resources/Private/Language/Backend.xlf';

    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        't3-form-icon-friendlycaptcha',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:db_friendlycaptcha/Resources/Public/Icons/friendlycaptcha.svg']
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('
        module.tx_form.settings.yamlConfigurations {
            1998 = EXT:db_friendlycaptcha/Configuration/Yaml/BaseSetup.yaml
            1999 = EXT:db_friendlycaptcha/Configuration/Yaml/FormEditorSetup.yaml
        }
    ');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        <INCLUDE_TYPOSCRIPT: source="FILE:EXT:db_friendlycaptcha/Configuration/TSconfig/Ext/FeManager/customfields.tsconfig">
    ');

    $extbaseObjectContainer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class);
    $extbaseObjectContainer
        ->registerImplementation(
            \In2code\Femanager\Domain\Validator\ServersideValidator::class,
            \BalatD\FriendlyCaptcha\Domain\Validator\ServersideValidator::class
        );
    $extbaseObjectContainer
        ->registerImplementation(
            \In2code\Femanager\Domain\Validator\ClientsideValidator::class,
            \BalatD\FriendlyCaptcha\Domain\Validator\ClientsideValidator::class
        );

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('femanager')) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Femanager\Domain\Repository\UserRepository::class] = [
            'className' => \BalatD\FriendlyCaptcha\Domain\Repository\UserRepository::class
        ];
    }
});
