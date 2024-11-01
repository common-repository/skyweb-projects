=== SkyWeb Projects ===
Contributors: makspostal, skywebsite
Tags: portfolio, projects
Requires at least: 5.2
Tested up to: 5.6
Requires PHP: 7.0
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Simple project catalog with AJAX filter.

== Description ==

SkyWeb Projects adds 'projects' post type with 'project-type' taxonomy witch is used for the AJAX filter.

= Main features =
* Additional project fields: 'Year', 'Price', 'Link'.
* AJAX projects filter.
* Gutenberg editor.
* Projects navigation (on the single project page).
* Comments (on the single project page).

= Demo =

[Projects list with AJAX filter](https://skyweb.site/#projects)

[Single project page](https://skyweb.site/projects/ets-cargo/)


== Frequently Asked Questions ==

= How can I change the plugin templates? =

You can copy template(s) `archive-projects.php` and (or) single-project.php into your theme (or child theme) and change it.

= How can I insert the AJAX projects filter into my page? =

You can use the shortcode `[skyweb_projects_filter]`.

*Note:* insert only one filter per page.

= How can I insert the projects list into my page? =

You can use the shortcode `[skyweb_projects_list]`.

*Note:* insert only one projects list per page.


== Installation ==

= Using The WordPress Dashboard (Recommended) =

1. Go to `Plugins` → `Add New`.
2. In a search field type **SkyWeb Projects** and hit enter.
3. Click `Install Now` next to **SkyWeb Projects** by SkywebSite.
4. Click `Activate the plugin` when the installation is complete.

= Uploading in WordPress Dashboard =

1. Go to `Plugins` → `Add New`.
2. Click on the `Upload Plugin` button next to the **Add Plugins** page title.
3. Click on the `Choose File` button.
4. Locate **skyweb-projects.zip** on your computer.
5. Click the `Install Now` button.
6. Click `Activate the plugin` when the installation is complete.

= Using FTP (Not Recommended) =

1. Download **SkyWeb Projects.zip**.
2. Extract the **SkyWeb Projects** directory to your computer.
3. Upload the **skyweb-projects** directory  **/wp-content/plugins/**
4. Go to `Plugins` → `Installed Plugins`.
5. Click `Activate` under **SkyWeb Projects** plugin title.

== Changelog ==

= 1.0.2 =

* Improved projects navigation.

= 1.0.1 =

* Added filter `skyweb-projects-add-fields` to change a project fields.
* Added `Free` project price text if the project's price is set to zero.

= 1.0.0 =

* Initial Release.


== Screenshots ==

1. Projects list with AJAX filter. Front.
2. Single project page. Front.
3. Projects list. Dashboard.
4. Projects types list. Dashboard.
5. Add a new project page. Dashboard.