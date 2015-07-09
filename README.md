# SIDEKICK -> cPanel -> Softaculous
This Softaculous mod will add the SIDEKICK plugin to the list of options for WordPress one click installation process.

##Installation

These files should be placed into this folder:

	/usr/local/cpanel/whostmgr/docroot/cgi/softaculous/conf/mods/wp/ 

If **conf/mods/wp** folders don't exist they have to be created. Once the mod has been uploaded it will show up during the one click **WordPress** installation process.

##Options

The **sk_settings.php** file contains specific configuration to your account and should not be changed. This configuration file will be provided by the SIDEKICK team.

* **SK\_DISTRIBUTOR\_ID** - Distributor identification number
* **SK\_CUSTOM\_CLASS** - Custom CSS class for distributor
* **SK\_FIRST\_USE** - This will open Sidekick drawer once when the user logs in
* **SK\_ACTIVATION\_REDIRECT** - This will redirect user to the Sidekick settings page once when the user logs in

##Activation Process

When the user goes through the one click Softaculous activation process SIDEKICK will be an plugin option on the installation screen. After WordPress installation is done the plugin will be pulled directly from the WordPress.org repo and activated for the user automatically.