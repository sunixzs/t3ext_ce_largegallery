# ce_largegallery

This extension provides a content element where you can choose a folder to show all jpg-files in a grid in frontend.

A maximum of nine images are directly shown on page load. If there are more than nine images a button is displayed to load the next twelve images via XHR and so on.

In the frontend there is also a litte JavaScript Lightbox-Gallery to open the thumbnails in a larger single view.  
The link to the large images points directly to the original files in storage. Upload images which are not too large or set a _maxWidth_ and/or _maxHeight_ in the ViewHelper. But keep in mind that each image must be resized by the image processor if larger than defined. Don't kill the page load and give the server a rest.

Technically this is an interaction with a normal content element to show some images and to provide an ajax call to the second part, an extbase plugin, to load some more images.

## Installation

### Get the package

Keep in mind that the packages name is `t3ext_ce_largegallery` and the extension key is `ce_largegallery`!

#### Checkout as git submodule

Example in _composer_ installation:

``` bash
$ cd typo3_composer_installation
$ git init
$ git submodule init
$ git submodule add https://github.com/sunixzs/t3ext_ce_largegallery.git public/typo3conf/ext/ce_largegallery
```

Don't forget to add classes to `composer.json` if you're using composer:

```
{
    "autoload": {
        "psr-4": {
            "MAB\\CeLargegallery\\": "public/typo3conf/ext/ce_largegallery/Classes"
        }
    }
}
```

#### Or checkout directly in extension directory:

Example in _classic_ installations:

``` bash
$ cd typo3_root/typo3conf/ext
$ git clone https://github.com/sunixzs/t3ext_ce_largegallery.git ce_largegallery
```

#### Or even without git

1. Load the zip file.
1. Unzip it and rename the folder from `t3ext_ce_largegallery-v1.0.0` to `ce_largegallery`.
1. Zip the renamed folder. The name now should be `ce_largegallery.zip`.
1. Go to the extension manager module and upload the package.

... or just copy the contents of the zip to a folder named `ce_largegallery` in the extension directory.

### Install in Typo3

1. Goto backend and enable extension in extension manager.
1. Open root page and add `Contentelement Largegallery` to `Resources -> TSConfig` to enable content element wizard.
1. Open Template and add `Contentelement Largegallery` to `Include -> Static` to load static constants and setup from extension.  
The static template should be loaded after `Fluid Content Elements` b/c this extension depends on and `fluid_styled_content` clears the view settings with `lib.contentElement >`.

## Configuration

### 1. Copy the templates to your templating stuff

``` bash
$ cd document_root
$ mkdir fileadmin/Resources/Plugins/ce_largegallery
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Private
$ cp -R typo3conf/ext/ce_largegallery/Resources/Private/* fileadmin/Resources/Plugins/ce_largegallery/Private/
```

### 2. Copy the public part

``` bash
$ cd document_root
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Public
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Public/style
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Public/script
$ cp typo3conf/ext/ce_largegallery/Resources/Public/style/Largegallery.css fileadmin/Resources/Plugins/ce_largegallery/Public/style/
$ cp typo3conf/ext/ce_largegallery/Resources/Public/script/Largegallery.js fileadmin/Resources/Plugins/ce_largegallery/Public/script/
```

The CSS- and JS-files are included with _requirejs_. If you use _requirejs_ adjust the path in _fileadmin/Resources/Plugins/ce_largegallery/Private/Templates/Largegallery.html_:

``` html
[...]
<script>
require([
    "path/from/require/base/to/script/Largegallery.js",
    "css!path/from/require/base/to/style/Largegallery.css"],
    function(Largegallery) {
    [...]
});
</script>
[...]
```

`path/from/require/base/to/` depends on your require-config. This means if your scripts are location in `fileadmin/Resources/assets/script/` the path would be `../../Plugins/ce_largegallery/Public/script/Largegallery`.

The CSS-file (`css!...`) is also loaded with an requirejs plugin which is not part in a standard case.

If you don't use _requirejs_ or you brew your own stuff then you've to load the files with typoscript or copy it or however you know what to do:

``` typoscript
page.includeCSS.LargeGallery = fileadmin/Resources/Plugins/ce_largegallery/Public/style/Largegallery.css
page.includeJS.LargeGallery = fileadmin/Resources/Plugins/ce_largegallery/Public/script/Largegallery.js
```

Then you also have to remove the `require()`-wrap in template-file and the `define()`-wrap in the js-file.

There is an unminified version in `Resources/Public/script/src/Largegallery.js`.

### 3. Change view settings in the template in field constants

``` typoscript
plugin.tx_celargegallery {
    view {
        templateRootPath = fileadmin/Resources/Plugins/ce_largegallery/Private/Templates/
        partialRootPath = fileadmin/Resources/Plugins/ce_largegallery/Private/Partials/
        layoutRootPath = fileadmin/Resources/Plugins/ce_largegallery/Private/Layouts/
    }
}
```

### Amount of images, file extensions to show, ...

In [Configuration/TypoScript/constants.typoscript](Configuration/TypoScript/constants.typoscript) there are settings for:

| Name                  | Type      | Default   | Description |
| --------------------- | --------- | --------- | ----------- |
| imagesOnPageLoad      | integer   | 9         | Defines the amount of images which are shown in content element on page load |
| imagesOnAjaxLoad      | integer   | 12        | Defines the amount of images which are loaded when using the _load more_-button via XHR in extbase plugin |
| fileExtensionFilter   | string    | jpg,jpeg  | When reading the folder the files will be filtered by these file-suffixes |
