=== Hooksure ===
Contributors: stingray82, reallyusefulplugin
Tags: webhook, form integrations, automation, forms
Requires at least: 6.5
Tested up to: 6.8.2
Stable tag: 1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Hooksure allows you to map SureForms, form submissions to webhooks dynamically within your WordPress admin dashboard without needing the pro plugin.

== Description ==

Hooksure provides a straightforward way to manage webhooks for your SureForms forms.

Hooksure handles sending webform submissions created in SureForms to your webhook of choice, i.e. Flowmattic, Bit Flows or Pabbly Connect.

**Video Tutorial - Hooksure Setup Guide**  
[youtube https://www.youtube.com/watch?v=dg-UWhLsEuU]

[Try hooksure in a Sandbox](https://reallyusefulplugins.com/try-hooksure/)

It allows you to pass that data over to an external service to process it as you would any normal webhook.

Want to send data and receive data back to your SureForms but not use SureTriggers?  

Well, if you want to use Zapier, Flowmattic, Bit Flows, or Pabbly Connect, you can do this with Response for Sureforms, which handles the sending and returning of webform data.

[Try Response for Sureforms in a Sandbox](https://reallyusefulplugins.com/plugins/response-for-sureforms/)  

We've recently implemented dynamic form population and better error handling directly from Response for SureForms into HookSure, making it better than ever than its initial launch.

== Installation ==

1. Upload the `hooksure` folder to the `/wp-content/plugins/` directory or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to **Tools → Hooksure** to start mapping your forms to webhooks.

== Frequently Asked Questions ==

= How do I update a form-webhook mapping? =
Just select the applicable form, paste your new webform URL as you would if adding a webhook for the first time, and save. It will overwrite the webhook URL with the new one.

= How do I delete a form-webhook mapping? =
In the admin interface, locate the mapping in the **"Existing Form Webhooks"** table and click **"Delete."**

== Screenshots ==

1. **Hooksure Plugin Dashboard**  
   ![Hooksure Plugin Dashboard](assets/screenshot-1.png)  
   The Main Plugin Dashboard:
   - Select the applicable webform
   - Add your Webhook URL
   - Delete the current webhook
   - Form ID

== Changelog ==

= 1.2 =

- Updated WP Version 6.8.2
- Tested Using Latest SureForms

= 1.15 =
- Fix Readme Issues  

= 1.14 =
- Updated Readme File  
- Added Screenshot and Video Links  

= 1.13 =
- Added Requires requirement for SureForms  

= 1.12 =
- Handle SureForms not being installed before installing HookSure gracefully  
- Add `wp-safe_redirect` on deletion to prevent loop of deletion of newly added hook to the same ID that was just deleted  

= 1.11 =
- Fixed Plugin Description issue  

= 1.1 =
- Added dynamic form fetching to replace manual form ID entry  
- Improved admin interface for better usability  
- Tested compatibility with WordPress 6.4  

= 1.0 =
- Initial release  

== Upgrade Notice ==

= 1.1 =
Upgrade now to enjoy the new **dynamic form fetching** feature, making webhook mapping easier than ever!
