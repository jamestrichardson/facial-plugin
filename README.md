# facial-plugin

A PiWiGo plugin to do some facial recognition

* Internal name: `facial` (directory name in `plugins/`)
* plugin page: [PiWiGo Extention's](https://piwigo.org/ext/index.php?eid=1008)

## Development Status

Active.

* Use phpdoc: https://www.sitepoint.com/introduction-to-phpdoc/

## Introduction

This plugin is to facilitate identifing which pictures do have faces within
them, and then whose face that belongs to. So, more plainly:

1. On the administration of `albums` page we want an option to "Scan for faces"
2. Perform the scan on all of the photos within that album
3. Identify each face (or place a placeholder of "Unknown person #3089" to it)

To that effect, we will write code for:

1. An admin panel, where the admin can admin the adminy stuff?
2. An extension to the albums page so that we can scan albums (we might instead use batch manager to select photos that way?)
3. Another event hook that will display the people identified in the photo

## The Admin Page

From the `admin` page, we want to do several things:

* List all of the known faces
* Create a face
* Train a face

## The Database

The following tables are created as part of this plugin:

* Known People
