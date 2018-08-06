# **WoodChippr**

**A highly opinionated workflow and development framework for wordpress**

WoodChippr uses Timber for bringing better separation of concerns in relation to application structure, and the Bones starter theme to provide some niceties for bringing wordpress under control.

This theme comes with a large array of opinionated defaults. This includes the following plugins:

- [Force Regenerate Thumbnails](https://en-gb.wordpress.org/plugins/force-regenerate-thumbnails/): for those irritating times when you need to add a new image size and regenerate all images
- [Yoast SEO](https://en-gb.wordpress.org/plugins/wordpress-seo/): For SEO
- [Imsanity](https://en-gb.wordpress.org/plugins/imsanity/): To stop users uploading stupidly large images

By default **WoodChippr** includes some tracking code in the header. If you don't change this it will remind you with a big ass h1 before your site code starts. To change this either edit `views/partials/tracking.twig`, or change `$context['analyticscode']` in `functions.php`

## **Who should use this project?**

Anyone who has experience creating Wordpress themes, and tends to use Wordpress more as a web framework than a blogging engine. This project aims to extend the famous 5 minute install process for theme developers, by giving them a complete development environment complete with package management and frontend development tooling out of the box.

**PLEASE NOTE:** As this framework is designed for theme developers, the actual theme itself is the bare essentials needed to make a theme appear in the Wordpress appearance section, with a few extras thrown in to provide helper functionality.

## Installation:

- Place files on server
- `npm install`
- `composer install`
- `npm run build` to minify assets
- Fill out and rename sample.env to .env and wp-config sample.

## Dev Env

- Install Node
- `npm install`
- `npm run setup` (if you want to install ACF)
- `npm start`
- 'composer install' to add new php modules

## Requiring plugins

Depending on the plugin here are two recommended approaches to requiring plugins for your theme:

1. Include the plugins in the `composer.json` file, this will install the mu-plugins folder.
2. Run `composer create-project tgmpa/tgm-plugin-activation --no-dev` in your theme directory, it is recommended to do this in the `functions` directory. this will set up a [TGMPA](https://github.com/TGMPA/TGM-Plugin-Activation) instance in your theme, check `example.php` for instructions for stipulated recommended or required plugins for your theme. You should use this method if the plugin does more than provide utility functions for your theme and requires the plugin activation hook to fire.
