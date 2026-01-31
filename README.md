# 🚀 ZwiiCMS 14.1.00

Zwii is a database-less (flat-file) CMS that allows you to easily create and manage a web site without any programming knowledge. Designed for simplicity and performance, ZwiiCMS offers a complete solution for modern web content management.

ZwiiCMS was created by a talented developer, [Rémi Jean](https://remijean.fr/). It is now maintained by Frédéric Tempez, who continues to evolve the project with new features and constant improvements.

[Site](http://zwiicms.fr/) - [Forum](http://forum.zwiicms.com/) - [Initial version](https://github.com/remijean/ZwiiCMS/) - [GitHub](https://github.com/fredtempez/ZwiiCMS) - [Release Notes](CHANGELOG.md) - [Notes de version](CHANGES.md)

## ⚙️ Recommended configuration

* PHP 7.2 or higher (PHP 8.0+ recommended for better performance)
* .htaccess support (for URL rewriting and security)
* Disk space: minimum 50 MB
* Modern browsers: Chrome, Firefox, Safari, Edge

## 📄 License

Zwiicms - CMS open source
Copyright (C) 2026 Frederic Tempez

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

## 📋 Release Notes

* **English** : [CHANGELOG.md](CHANGELOG.md) - Release notes in English
* **Français** : [CHANGES.md](CHANGES.md) - Notes de version en français

## 📥 Downloading ZwiiCMS

To download the latest released version, go to:
* [the Updates page](https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS/releases) to access all versions
* or at [the site download page](https://zwiicms.fr/download) for stable version
* Automatic updates integrated in administration panel

## 🛠️ Installation

Unzip the Zwii archive and upload its contents to the root of your server or to a subdirectory. That's all!

You will find more explanations, in particular for an installation at Free, in the "Downloads" section of the forum.

**Installation highlights:**
* Installation in less than 5 minutes
* No database required
* Automatic security configuration
* Responsive design from installation

## 🔄 Update procedures

When installing a major version, it is recommended to make a backup copy.

**Version 14 new features:**
* Modernized administration interface
* Performance improvements
* Enhanced security
* Detailed site statistics
* Advanced user profile management
* Extended multilingual support

### 🤖 Automatic

* Connect to your site as administrator.
* If an update is available, a notification appears in the administration bar.
* Click on "Update" button and follow the instructions.
* The system automatically checks for updates every 24 hours.

### ✋ Manual

* Save your entire site, especially the "site" directory containing all your data.
* Unzip the new version on your computer.
* Transfer its content to your server by activating the file replacement (except the "site" folder).
* Check that file permissions are correct (755 for folders, 644 for files).

## 🌳 General tree structure

*Legend: [D] Directory - [FILE] File*

**Optimized structure for version 14:**

````
text
[DIR] core                   Core of the system
  [DIR] class                PHP framework classes
  [DIR] layout               Layout and templates
  [DIR] module               Core modules (page, config, etc.)
  [DIR] vendor               External libraries (TinyMCE, etc.)
  [FILE] core.js.php         JavaScript core (new cache system)
  [FILE] core.php            PHP core with v14 improvements

[DIR] module                 Page modules
  [DIR] blog                 Blog with RSS feed and comments
  [DIR] form                 Advanced form manager
  [DIR] gallery              Gallery with automatic thumbnails
  [DIR] news                 News system with RSS
  [DIR] redirection          URL redirections
  [DIR] download             Download area

[DIR] site                   Site content
  [DIR] backup               Automatic backups (logging)
  [DIR] i18N                 Interface languages (French, English, etc.)
  [DIR] data                 Data directory
    [DIR] en                  Localized folder
      [FILE] page.json        Page data (hierarchical structure)
      [FILE] module.json      Page module data
      [FILE] local.json       Language-specific site data
      [FILE] .default         Default site language indicator
      [DIR] content           Page content folder
        [FILE] home.html      Sample home page content
    [DIR] fonts               Folder containing installed fonts
      [FILE] fonts.html       File containing font calls to load on cdnFonts
      [FILE] fonts.css        File containing style sheet linked to local fonts
      [FILE] fonts.woff       Local font files (woff, etc..)
    [DIR] modules             Module customization or own data
    [FILE] admin.css          Administration pages theme (new v14 design)
    [FILE] admin.json         Administration pages theme data
    [FILE] blacklist.json     Logging of login attempts with unknown accounts
    [FILE] config.json        Site configuration
    [FILE] core.json          Core configuration
    [FILE] custom.css         Advanced customization stylesheet
    [FILE] font.json          Custom font descriptor
    [FILE] journal.log        Activities logging (automatic rotation)
    [FILE] language.json      Interface languages database
    [FILE] profil.json        User profiles (extended v14 system)
    [FILE] theme.css          Site theme
    [FILE] theme.json         Site database
    [FILE] user.json          User database
    [FILE] .backup            Marker for file backup if present
  [DIR] file                  File manager upload directory
    [DIR] source              Various resources
    [DIR] thumb               Image thumbnails (v14 optimization)
  [DIR] tmp                   Temporary directory

[FILE] index.php              ZwiiCMS initialization file
[FILE] robots.txt             Filtering of directories accessible to search engine robots
[FILE] sitemap.xml            Sitemap (automatically generated)
[FILE] sitemap.xml.gz         Compressed version

🔒 The .htaccess files contribute to security by filtering access to sensitive directories.
