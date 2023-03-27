# ZwiiCMS 12.3.04

Zwii is a database-less (flat-file) CMS that allows you to easily create and manage a web site without any programming knowledge.

ZwiiCMS was created by a talented developer, [Rémi Jean](https://remijean.fr/). It is now maintained by Frédéric Tempez.

[Site](http://zwiicms.fr/) - [Forum](http://forum.zwiicms.com/) - [Initial version](https://github.com/remijean/ZwiiCMS/) - [GitHub](https://github.com/fredtempez/ZwiiCMS)

## Recommended configuration

* PHP 7.2 or higher
* .htaccess support

## License

This work is licensed under the Attribution-Noncommercial-No Derivative Works 4.0 International License. 

To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/4.0/ or write to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA.

## Downloading ZwiiCMS

To download the latest released version, go to :
- [the Updates page](https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS/releases)
- or at [the site download page](https://zwiicms.fr/telechargement) 


## Installation

Unzip the Zwii archive and upload its contents to the root of your server or to a subdirectory. That's all!

You will find more explanations, in particular for an installation at Free, in the "Downloads" section of the forum.


## Update procedures

When installing a major version, it is recommended to make a backup copy.

### Automatic

* Connect to your site.
* If an update is available, it is proposed in the administration bar.
* Click on the "Update" button.

### Manual

* Save your entire site, especially the "site" directory.
* Unzip the new version on your computer.
* Transfer its content to your server by activating the file replacement.


## General tree structure

*Legend: [D] Directory - [FILE] File

````
text
[DIR] core Core of the system
  [DIR] class         Classes
  [DIR] layout        Layout
  [DIR] module        Core modules
  [DIR] vendor        External libraries
  [FILE] core.js.php  Javascript core
  [FILE] core.php PHP core

[DIR] module          Page modules
  [DIR] blog          Blog
  [DIR] form          Form manager
  [DIR] gallery       Gallery
  [DIR] news          News
  [DIR] redirection   Redirection

[DIR] site                Site content
  [DIR] backup            Automatic backups
  [DIR] i18N              Zwii Interface languages
  [DIR] data              Data directory
    [DIR] en              Localized folder
      [FILE] page.json    Page data
      [FILE] module.json  Page module data
      [FILE] local.json   Language-specific site data
      [DIR] content       Folder of page contents
        [FILE] home.html  Sample home page content
    [DIR] fonts           Folder containing the installed fonts
      [FILE] fonts.html   File containing the fonts calls to load on cdnFonts
      [FILE] fonts.css    File containing the style sheet linked to the local fonts
      [FILE] fonts.woff   Local font files (woff, etc..)
    [DIR] modules         Customization of modules or own data
    [FILE] admin.css      Theme of administration pages
    [FILE] admin.json     Theme data for administration pages
    [FILE] blacklist.json Logging of login attempts with unknown accounts
    [FILE] config.json    Site configuration
    [FILE] core.json      Core configuration
    [FILE] custom.css     Advanced customization stylesheet
    [FILE] fonts.json     Custom font descriptor
    [FILE] journal.log    Action logging
    [FILE] languages.json Interface database languages
    [FILE] theme.css      Site theme
    [FILE] theme.json     Site data
    [FILE] user.json      User data
    [FILE] .backup Marker for file backup if present
  [DIR] file              File manager upload directory
    [DIR] source          Various resources
    [DIR] thumb           Image thumbnails
  [DIR] tmp               Temporary directory

[FILE] index.php          ZwiiCMS initialization file
[FILE] robots.txt         Filtering of directories accessible to search engine robots
[FILE] sitemap.xml        Sitemap
[FILE] sitemap.xml.gz     Compressed version

The .htaccess files contribute to security by filtering access to sensitive directories.
