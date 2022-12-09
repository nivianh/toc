# Overview
This is a plugin for Botble CMS so you have to purchase Botble CMS first to use this plugin.

# Installation

- For developers:
    - Rename folder `toc-main` to `toc`.
    - Copy folder `toc` into `/platform/plugins`.
    - Run command `php artisan cms:plugin:activate toc` to activate this plugin.

- For non-developers:
    - Rename folder `toc-main` to `toc`.
    - Copy folder `toc` into `/platform/plugins`.
    - Or go to Admin Panel -> Plugins and activate plugin Table of Content.

- For Botble version >= 6.x
    - Set/Edit in `.env` file `CMS_ENABLE_MARKETPLACE_FEATURE=true`, if you already have it, skip it.
    - Go to `Admin Panel` -> `Plugins` menu -> ` + Add new` button.
    - Find or search `toc` keyword and `Install now`.
    - Good luck and enjoy!!

# Credits
- This plugin is referencing the source code from https://wordpress.org/plugins/easy-table-of-contents

# Versions
- Botble version <= 5.30 -> use `v1.1` (https://github.com/nivianh/toc/releases/tag/v1.1)
