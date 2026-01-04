# Release Notes

## Version 14.00.00

### Enhanced JsonDB Database

- **JSON Minification**: New feature reducing file size by 20-40% by removing unnecessary spaces
- **Smart Cache with Tags**: High-performance caching system with selective invalidation, 3-5x faster for frequent reads
- **Gzip Compression**: 50-70% disk space reduction with automatic data compression
- **AES-256-CBC Encryption**: Protection of sensitive data with optional encryption
- **Per-Database Configuration**: Administration interface to configure each database individually (cache, compression, encryption, minification)
- **Critical Database Protection**: config.json and core.json limited to minification only for security reasons
- **User Interface**: New "Configuration > Databases" section with card display (3 per row) and alphabetical sorting
- **Improved Backups**: Automatic version management and corruption detection
- **Complete Documentation**: Delivery of detailed documentation (JsonDb_Complete_Documentation.md and ZwiiCMS_JsonDb_Integration.md)

### System Improvements

- **Enhanced Log Rotation Method**: Optimized log management with automatic rotation
- **Router Optimization**: Code reorganization for better performance
- **Style Sheet Generation**: Optimization of CSS compilation process
- **Improved Administration Bar Animation**: Smoother and more modern transitions
- **Default Mailer Configuration**: Automatic setup if SMTP is not enabled

### User Interface

- **Database Configuration**: New responsive interface with modern cards
- **Alphabetical Sorting**: Databases automatically ordered
- **Grouped Options**: Minification + compression, cache + TTL, encryption + key on same rows
- **Visual Protection**: Disabled options grayed out for critical databases
- **Optimized Layout**: 3 cards per row with responsive layout

### Fixes and Optimizations

- **Default Configuration**: Optimized values in defaultdata.php for each database type
- **100% Compatibility**: All existing modules work without modification
- **Automatic Migration**: Transparent configuration update process
- **RSS Feeds**: Fixes image paths in RSS feeds with base URL prefix for Download and Blog modules.
- **Smart Tables**: Fixes confirmation errors (deletion) in smart tables.

### 📄 Documentation

- **JsonDb_Complete_Documentation.md**: Complete technical documentation with examples
- **ZwiiCMS_JsonDb_Integration.md**: ZwiiCMS-specific integration guide
- **Practical Examples**: Ready-to-use code for all features
- **Benchmarks**: Performance measurements and expected benefits

### 📜 License

- **License Change from CC to GPL 3**: ZwiiCMS switches from Creative Commons to GNU General Public License version 3
  - **Freedom of Use**: Commercial, personal, educational use authorized
  - **Modification Authorized**: Anyone can modify the software
  - **Distribution Authorized**: Redistribution possible under same conditions
  - **Copyleft**: Derivative works must be under same license
  - **Author Protection**: Attribution required and copyright preservation
  - **Legal Warranty**: No warranty is provided by the license

### 📊 Benefits

- **Performance**: 20-30% faster startup, 40-50% more responsive administration
- **Disk Space**: 50-70% reduction with compression, 20-40% with minification
- **Security**: Optional encryption and critical database protection
- **Reliability**: Automatic corruption detection and improved backups
- **Scalability**: Configurable cache and optimization per data type


## Version 13.11.03
- Fixes a bug preventing user account management.

## Version 13.11.02
- Fixes menu alignment bug.

## Version 13.11.01
Restore mirror code for JS and CSS editing

## Version 13.11.00
- TinyMCE editor update 5.10.9
- Cleanup of unnecessary TyniMCE plugins.
- CodeMirror update.
- Fixes non-clickable banner bug in theme editing.
- Adjustment of collapsible administration bar timing.

## Version 13.10.01
- Page editing, added page id cannot be a reserved core name.

## Version 13.10.00
- Collapsible administration bar, option to keep it fixed.
- Animation of site width change preview in administration.
- Optimized icon size of administration bar on small screens.

## Version 13.9.00
- Optimization of user ID retrieval method efficiency.
- Provides cleanRssText method to clean text outputs for RSS feeds.
- New site widths in theme.
- Router optimization.
### Fixes
- Fixes log file rotation.
- Fixes rewrite reading in configuration causing fatal error under Nginx.
- Small fixes in script managing file manager uploads.

## Version 13.8.00
### Changes 
- The ico() method admits a new disabled parameter. This property disables the href link, the icon is no longer clickable.
- Optimizes concurrent access search and speeds up page display.

## Version 13.7.01
### Fixes
- Fix of style sheet preloading method to avoid FOUC effect.
- Fix of tab position retrieval in Config view for current user.
- Activation of feature proposing redirect to login page if page requires authentication and user is not logged in.

## Version 13.7.00
### Optimizations and improvements
- Optimization of core database classes.
- Adds cache to JSON file management system.
- Completes icon library.
- Adds options to Datatables script.
- Password reset possibility from email address.

### Security fixes:
- Securing file manager access.
- Fixes path access bug for resources published by non-administrator members in image input fields.
- Removes path normalization in page content, path normalization is done by inserting absolute rather than relative URLs in TinyMCE.
- Rewrite of article truncation method.
- Fixes warning in installation window.
- Fixes bugs in RSS feed generation for Download and Blog modules.

## Strengthening permission controls on modules and pages:

  - Modules are no longer displayed in page configuration page without permission.
  - Module button no longer appears in administration bar when profile does not have permission to configure page module.
  - Pages not accessible to certain roles are no longer visible in menu bar, in sub-page menus.

## Version 13.6.09
- Removes maintenance mode during update.
- Increases maximum dimension of blog comments.

## Version 13.6.08
- Fixes CVE-285 vulnerability: Improper Authorization.
- Removal of ZWII_USER_ID cookie for authentication
- Fixes password reset bug.
- Improves security and robustness of core functions:
- secure_file_put_contents: parameter validation and integrity check
- sendMail: email validation and HTML entity securing
- genererIdAlphanum: replacement of str_shuffle with secure random_int
- setData: exception handling and data structure validation

## Version 13.6.07
- Improves class managing inputs/outputs, improved save method (temporary file lock).
- Page editing, saves extension tab position in CSS and JS editing.
- Fixes module access icon in page editing.
- Fixes JS and CSS data persistence after page renaming.
- Gallery module removal of an echo.
- Resource module, fixes inactive control buttons.

## Version 13.6.06
- Fixes a bug in profile deletion rights management.

## Version 13.6.05
Fixes dysfunctions in profile and shared folder management.
- Incorrect format of profile addition page (unclosed division).
- Still variables noted Group instead of Role.
- Profile addition, impossible to validate.
- Geogallery 1.5: profile addition window format.
- Optimization and updating of htaccess directives.
- Window framing "light", CSS compatibility with Safari
- getUserPath method: 
    - Detects software version (CMS or Campus) to read correct sharing keys.
    - Returns "none" if no authorization, value stored in DB.

### Fixes:
- getUserPath method operation, discrimination between CMS and Campus.
- Profiles: default path at installation, one too many slashes at beginning of string.
- Displays message when user does not have sufficient rights to access a share in folder selector.

## Version 13.6.04
- Unification of folder display method in gallery and geogallery modules. Henceforth, the list of proposed folders is consistent with the folder configured in the profile.
- Improvement of geogallery module.
- Implementation of new method to upload file without going through file manager. This method interests module developers.
- Dissociation of page permissions and those of a module: restricted access to a module page no longer generates 403 error on entire page. Module content is simply ignored. This situation only concerns developers whose modules need an account to function like the upload module, see $actions array in class declaration.

## Version 13.6.03
- Repository change to https://codeberg.org/fredtempez/
- Fixes a bug in profile ID creation.

## Version 13.6.02
Translations:
  - Fixes regression in language management module preventing modification of localized site parameters.
  - Fixes switching to French of default language after site configuration submission.
  - Labels in theme and footer.

## Version 13.6.01
### Fixes & code improvements
- Translations:
  - Interface language switching during installation. Feature disabled when sessions are cached.
  - Translation improvements.
- InputFileDelete button height.
- Avoids looping on login page when home page is not accessible due to inconsistency in language folder management.
- Automatic update:
  - Modified maintenance mode activation during an update.
  - Adds warning about rewrite in case of disconnection.
  - Error messages formatted in HTML.
  - Displays error even if disconnection message is raised.
- Gallery module 4.4, fixes manual sorting of galleries and images in a gallery
- Helper: table template benefits from new parameter to name table rows, essential for manual sorting script of galleries and images in gallery.

## Version 13.6.00
**Improvements:**
- User groups become roles.
- PHP 8.3 compatibility
- Hardening of security during password reset.
- Log file rotation (more than 4 MB and 5 compressed archives).
- Support for vector images deposited in file manager, a copy is created in thumbnail folder.
- Addition of border effect around installer and automatic update pages.
- Inversion of user identity, names before first names.
- Rewrite of JsonDB class save method. A temporary data file is generated to avoid writing to original file.
- Improvement of event management script in page editing.
- Startup error (loading php extensions), formatting of error page.
- Lax parameter of ZWII_UI cookie.
- Password reset, new label in case of email sending error.
- Date template, fixes bug when custom column IDs are passed in last parameter.
- Syntax fixes.

**Fixes:**
- When adding a new user, in case of input error and form validation, confirmation email is not sent.
- Page editing, memorization of last tab now occurs on form submission. Page no longer reloads on each tab change, which had harmful effects.
- Code cleanup and small fixes.
- Form module 4.6, small fix.

**Developers:**
- New *number()* template method to create input field accepting min, max, step and pattern properties.
- New *required* property for mandatory text input templates. Generation of marker on field label and interception of invalid field by browser which displays popup.

## Version 13.5.03
**Fixes**
- Bad recovery of user group, user management module.
- Small fixes.

## Version 13.5.02
**Fix**
- Password reset did not work. Password change failure is recorded in Zwii log.

## Version 13.5.01
**Fix**
- Site configuration, form not submitted message not displayed.

## Version 13.5.00
**Improvements:**
- After tab change in configuration page, page no longer reloads. Last tab displayed before a submit is memorized in user views.
- Reactivation of language dialog editing.
- New server SMTP proper functioning test button to validate double authentication.
- Validation of site connection with code sent by email. Option available from site configuration, connection tab. It activates by ascending group, example "editor" for editors and administrators.
- Optimization of class variable loading.
- Removal of charset declaration redundancy.
- Optimized backup method.

**Fixes:**
- Fixes display bug of edit button of a page containing a module that was deleted without going through management interface (FTP).
- Fixes password change bug for non-administrator accounts.
- Blog 7.12, fixes article display bug when Moderne theme is selected.
- Fixes dysfunction of truncation function that disturbed blog article display.
- Activation of active tab memorization in configuration after form validation or visit of another site page.
- At installation, back button memorizes selected language at first step.
- Fixes bug of online update of language file.

## Version 13.4.00
** Improvements:**
- Changes authentication mode, password hash is no longer stored in a cookie.
- Improves write operation performance.
- File selector displays file path present in field in file manager.
- Strengthened persistent connection.
- Datatables.net script filtering elements, number of elements and position on screen.
- Slider 7.2, selected folder is displayed by default in update page.
- Increases thumbnail size after transfer in RFM.
- Search 3.3 performs only one reading of module.json file to prevent aggressive bots.
- Modification of robots.txt content to only allow search engines and prohibit bots.
- Removal of cookies memorizing last tab displayed in page editing and site configuration. This information is now stored in connected user's record.

**Fixes:**
- Changes consent cookie parameters.
- Isolates session in active tab.
- Page editing, delete and duplicate pointing to wrong page.
- Removes warning at page creation.
- Site generation button inoperative.
- Intermittent display of page navigation buttons in module views.


## Version 13.3.06
** Fixes: **
- Fixes security bug in profile management.
- Repairs delete button in page edit mode. 
- Fixes slider style sheet for H1, H3 and A tags.
- Fixes absence of page content when module is in free position and [MODULE] keyword has not been inserted.
- Fixes "Stay logged in on this browser" option which connection is now truly persistent.
- Removes slash at end of catalog URL. 
- Avoid warning when creating a new page.

## Version 13.3.05
** Fixes: **
- Fixes thumbnail generation in avif and webp format.
- Fixes FLOAT filter of helper that removed floating point comma.
- Fixes bugs in Slider module which becomes version 7.0, addition of image label option.

** Changes: **
- News module 5.9 update, button size.
- Blog module 7.10 update, blocks submission of empty comment.
- Adds DATE and TIME filters for correct form field display.

## Version 13.3.04

** Fix: **
- Bad generation of module descriptor during its save. 

## Version 13.3.03

** Fixes:**
- Firefox page editing bug is fixed thanks to removal of command forcing lazy loading of images in core.js.php
- In configuration, Apache smart URL option only activates if server is Apache and Rewriter module is active. This excludes other incompatible servers like Nginx, Caddy etc.
- Adding slash at end of address with active rewrite caused bad determination of image addresses in TinyMCE. Resolution: an htaccess directive removes all slashes at end of address.
- When page is open in editing, a click on edit button in administration bar displays error, link being incorrect. To avoid this error and redundancy, edit button is hidden when page is edited.
- When useless elements are added to a page address, a 403 error is raised.

## Version 13.3.01 - 13.2.02

Delivery of blog and news modules fixing RSS feed problem with empty metas.

## Version 13.3.00

This modification avoids problems of editing different languages in different tabs of same browser.

## Version 13.2.02

Fixes warning when a blog or news module contains no article.

## Version 13.2.01

### Fix

Modification of jsonDB class data writing function to ensure integrity of written data. Intense peak traffic on large files and on a low-power server could cause write errors or bad json data formatting.

## Version 13.1.08

### Fixes

- Fixes errors when a parent page or child pages have limited permissions.
- Search module 3.1: module initialization after installation in a page without user configuration.

### Improvements

- Saves state of selectors in font and user tables.
- Adds integrity checks of Json databases during load and save operations.
- Provides interface for automated backup control and their cleanup by CRON script.

## Version 13.1.07

### Fixes

- Fixes deprecation of Usort function output in RFM.
- Slider module 6.4: fixes several bugs in sorting functions

## Version 13.1.06

### Fixes

- User addition, label not saved.
- Slider, fixes sorting not taken into account.

## Version 13.1.05

### Fixes

- Definitely fixes delete function of dot class.
- Incorrect update branch of version 13.1.04.

## Version 13.1.04

### Fixes

- Avoids notice in social tab of configuration when opengraph image is not specified.
- Fixes error in dot class (delete function called statically)
- Does not redirect to error page (403 or 404) after login.

### Improvements

- Online update: restoration of rewrite in root .htaccess file, use of *.rewrite* flag file to preserve rewrite state in order to dedicate ```data``` variable (XHR) to error messages.
- Logs automatic update errors.


## Version 13.1.03

### Fixes

- Fixes date format in sitemap generation.
- Limits display of file manager icon in menu to simple members.
- Blog module; time variable format.
- Blog and News modules, fixes wrong localization parameter bug.
- Forgotten password, undefined variable displaying warning.

### Improvements

- Themes; fonts: fonts assigned in administrator theme are taken into account in font deletion blocking.
- Optimization of discrimination code.

## Version 13.1.02

### Fixes

- Theme; fonts: fixes problem during installation and uninstallation of a font in woff file format.
- Theme; fonts: fixes redirection bug to font list after editing or addition.
- User; addition: fixes non-taking into account of language.
- Removes imagemap script not maintained and buggy.

### Improvement

- Themes; fonts: filtering and sorting of installed fonts.

## Version 13.1.01

### Improvements

- This version removes theme selector during installation.
- News and blog modules:
  - New option adding a return button in article page.
  - Signature block formatting is homogenized.
- Member profiles, fixes impossibility to authorize file manager access for non-administrator members.

### Fixes

- Fixes bad formatting of event log.
- Other small fixes.

## Version 13.1.00

### Improvements

- User management benefits from new features:
  - An optional label field can be assigned to users, it contains keywords separated by spaces. These keywords allow filtering users.
  - A dynamic search field acting on all table columns filters rows.
  - Number of displayed elements is dynamic.
  - Import procedure takes into account label field and adds profile field.
- Assigns home icon to return buttons.
- Adds return link to 403 and 404 error pages.

### Fixes

- Fixes unspecified permission problem in a profile.
- Fixes deprecation messages in RFM.
- Fixes deprecation message related to possible absence of short title in sitemap generation.
- Fixes page list generation according to selected language for TinyMCE links
- Updates profiles in slider.
- Small fixes.

## Version 13.0.08

### Fixes

-Fixes a bug in sitemap generation.

## Version 13.0.07

### Fixes

- Slider module (version 6.1), image width compliance in full width when site is 100% width.
- Extension management, creation of "Modules" folder missing when copying a module archive in file manager.
- Fixes bug in sitemap generation when site contains no visible page.
- Avoids warning messages if store is inaccessible.

## Improvements

- Administrators have possibility to force password change without knowing current password.
- Module profile management, terms are more precise (*Add article* instead of *Add*).
- Inversion of button order in login window on small screens.
- Modifications of toolbar and menu of TinyMCE text editor.

## Version 13.0.06

### Fixes

- Fixes warning triggered when navigation buttons are placed on an orphan page.
- Removes screen widths in percentages below 100%.

## Version 13.0.05

### Fix

- Unspecified email sending address causing sending rejection.

### Improvements

- Adds member filters, groups/profiles, first names and names starting with certain letter.
- Restores error management at step 4 of automatic update.
- New screen sizes in percentages from 70% to 100% by 5% steps.
- Move of signature function from core.php.
- Doubling of operators in login window captcha.

## Version 13.0.04

### Fixes

- Fixes security bug. When a profile has file manager access rights and no folder is selected, site root was displayed.
- Fixes display problem of profile comments in account editing.
- Error editing a level 1 profile, example simple member.
- Simple member profile always displayed file manager in all cases.
- Mass user import, download template button was inoperative.

### Improvements

- Removes error management at step 4 of automatic update.
- Modifies update download URL.
- Improves date display when site is displayed in foreign language.

## Version 13.0.03

### Improvements

- Move of language management button to right of language selector in administration bar. Language selector is always displayed even if French is the only available language.
- Removal of unnecessary calls to CSRF control function.
- Removal of functions related to module data management containing various bugs.

# Fixes

- Password reset message not sent.
- Completes error message when PHP modules are missing.
- Footer links take color defined in site.
- Form module 4.1: fixes email not sent after form validation.
- Blog module Version 7.1: permission during form validation

## Version 13.0.02

### Fixes

- Font database renaming bug

## Version 13.0.01

### Fixes

- Bad presentation of icon before child pages in TinyMCE link list.
- Redirection module: page or module editing impossible.

## Version 13.0.00

### Improvements

- User groups become roles.
- PHP 8.3 compatibility
- Hardening of security during password reset.
- Log file rotation (more than 4 MB and 5 compressed archives).
- Support for vector images deposited in file manager, a copy is created in thumbnail folder.
- Addition of border effect around installer and automatic update pages.
- Inversion of user identity, names before first names.
- Rewrite of JsonDB class save method. A temporary data file is generated to avoid writing to original file.
- Improvement of event management script in page editing.
- Startup error (loading php extensions), formatting of error page.
- Lax parameter of ZWII_UI cookie.
- Password reset, new label in case of email sending error.
- Date template, fixes bug when custom column IDs are passed in last parameter.
- Syntax fixes.

### Fixes

- When adding a new user, in case of input error and form validation, confirmation email is not sent.
- Page editing, memorization of last tab now occurs on form submission. Page no longer reloads on each tab change, which had harmful effects.
- Code cleanup and small fixes.
- Form module 4.6, small fix.

### Developers

- New *number()* template method to create input field accepting min, max, step and pattern properties.
- New *required* property for mandatory text input templates. Generation of marker on field label and interception of invalid field by browser which displays popup.
