# CodeIgniter Language Switch

Allows for the switching of languages within CodeIgniter, with a focus on support for SEO friendly URLs, eg.

- http://yoursite.com/welcome (English)
- http://yoursite.com/bienvenu (French)
- http://yoursite.com/bienvenidos (Spanish)

SEO URLs default to using language codes if no translation is provided, eg.

- http://yoursite.com/fr/contact (French)
- http://yoursite.com/es/contact (Spanish)

Language codes can also be set in config as a default as a directory (above) or in query strings, eg.

- http://yoursite.com/contact?lang=fr (French)
- http://yoursite.com/contact?lang=es (Spanish)

## Requirements

1. CodeIgniter 2+

## Installation and use

1. Copy files to relevant places, if files already exist (eg. config/autoload.php) copy code into existing files
2. Configure your languages in config/languages.php
3. Create your URL translations for SEO links
4. Put links to change languages in your views using "language_menu()"

## Notes

- You will need to use anchor() or site_url() for all internal links, you should be doing this anyway
- Currently SEO links only support REQUEST_URI, you will need to change code if you want support for other URI protocols