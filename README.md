# Aucor Core
Core plugin for WordPress projects. The plugin contains the features and settings generally deemed to be the most commonly used in all projects. It is meant to be used together with [aucor-starter](https://bitbucket.org/aucor/aucor-starter/overview) and [aucor-starter-plugin](https://bitbucket.org/aucor/aucor-starter-plugin/src/master/), but functions on it's own as well. Use the aucor-starter-plugin to configure the specs of this plugin.

## How to install

1. Download this repository from bitbucket.com (from left sidebar)
1. Extract into /plugins/

## Contents

### Abstract Classes

Directory: root

The models the features are built on

### Features and subfeatures

Directory: `/features/`

Features (containing subfeatures) ranging from security settings to speed optimizations and dashboard cleanup.

- admin
  - gallery
  - image-links
  - login
  - admin menu cleanup
  - notifications
  - profile cleanup
  - remove customizer
- classic-editor
  - tinymce
- dashboard
  - cleanup
  - recent widget
  - remove panels
- front-end
  - excerpt
  - html fixes
- localization
  - polyfill
  - string translations
- plugins
  - acf
  - gravityforms
  - redirection
  - seo
  - yoast
- security
  - disable file edit
  - disable unfiltered html
  - head cleanup
  - hide users
  - remove comment moderation
  - remove commenting
- speed
  - limit revisions
  - move jquery
  - remove emojis
  - remove metabox

### Debugging

Directory: `/helpers/`

Custom function to help debugging.

## Configuration (optional)

### How to configure

1. If you haven't already, download the Aucor Starter Plugin from [Bitbucket](https://bitbucket.org) and follow it's installation instructions
1. The fetures listed above can all be disabled if necessary with filters: `add_filter('[insert feature key]', '__return_false');`
