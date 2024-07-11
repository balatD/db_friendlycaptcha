# Friendly Captcha - a TYPO3 Extension

### **STILL WIP**

A TYPO3 Extension that brings the Friendly Captcha functionality to EXT:form, based on the work of EXT:recaptcha.

## Breaking Change

The extension key was changed from db_friendlycaptcha to friendlycaptcha.

As a consequence all pathes referenceing file from the extension are changed and
TypoScript inclusions needs to be checked. Most likely inclusions in sitepackages
like

```@import 'EXT:db_friendlycaptcha/Configuration/TypoScript/setup.typoscript'```

need to be replaced with

```@import 'EXT:friendlycaptcha/Configuration/TypoScript/setup.typoscript'```

## Installation
### Composer
The recommended way to install TYPO3 Console is by using [Composer](https://getcomposer.org):

    composer require balatd/db_friendlycaptcha

