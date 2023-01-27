# Friendly Captcha - a TYPO3 Extension

### **STILL WIP**

A TYPO3 Extension that brings the Friendly Captcha functionality to EXT:form, based on the work of EXT:recaptcha.

## Installation
### Composer
The recommended way to install TYPO3 Console is by using [Composer](https://getcomposer.org):

    composer require balatd/db_friendlycaptcha

### Configuration

extend your typo3conf/LocalConfiguration with the following:

    'db_friendlycaptcha' => [
        'lang' => 'de',
        'private_key' => 'XXXXXXXX',
        'public_key' => 'XXXXXXXXX',
        'verify_server' => 'https://eu-api.friendlycaptcha.eu/api/v1/siteverify',
        'puzzle_server' => 'https://eu-api.friendlycaptcha.eu/api/v1/puzzle',
    ],

leave verify_server and puzzle_server empty for global endpoint
