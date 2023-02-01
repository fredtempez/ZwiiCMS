# ZwiiCMS 12.2.01

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

*Legend: [R] Directory - [F] File

````
text
[R] core Core of the system
  [R] class Classes
  [R] layout Layout
  [R] module Core modules
  [R] vendor External libraries
  [F] core.js.php Javascript core
  [F] core.php PHP core

[R] module Page modules
  [R] blog Blog
  [R] form Form manager
  [R] gallery Gallery
  [R] news News
  [R] redirection Redirection

[R] site Site content
  [R] backup Automatic backups
  [R] data Data directory
    [R] en Localized folder
      [F] page.json Page data
      [F] module.json Page module data
      [F] local.json Language-specific site data
      [R] content Folder of page contents
        [F] home.html Sample home page content
    [R] fonts Folder containing the installed fonts
      [F] fonts.html File containing the fonts calls to load on cdnFonts
      [F] fonts.css File containing the style sheet linked to the local fonts
      [F] fonts.woff Local font files (woff, etc..)
    [R] modules Customization of modules or own data
    [F] admin.css Theme of administration pages
    [F] admin.json Theme data for administration pages
    [F] blacklist.json Logging of login attempts with unknown accounts
    [F] config.json Site configuration
    [F] core.json Core configuration
    [F] custom.css Advanced customization stylesheet
    [F] fonts.json Custom font descriptor
    [F] journal.log Action logging
    [F] theme.css Site theme
    [F] theme.json Site data
    [F] user.json User data
    [F] .backup Marker for file backup if present
  [R] file File manager upload directory
    [R] source Various resources
    [R] thumb Image thumbnails
  [R] tmp Temporary directory

[F] index.php ZwiiCMS initialization file
[F] robots.txt Filtering of directories accessible to search engine robots
[F] sitemap.xml Sitemap
[F] sitemap.xml.gz Compressed version

The .htaccess files contribute to security by filtering access to sensitive directories.
