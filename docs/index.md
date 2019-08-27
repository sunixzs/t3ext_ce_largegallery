---
title: Inhaltselement Große Bildergallerie
layout: default
---

# Inhaltselement Große Bildergallerie

Eine Typo3-Extension mit einem Inhaltselement zur Ausgabe von vielen Bildern aus einem Ordner in der Dateiliste.

| Author | {{ site.author }} <{{ site.author_email }}> |
| Build: | {{ site.time }} |
| Version | {{ site.version }} |


## Installation

> Das Paket heißt `t3ext_ce_largegallery` und läuft in Typo3 mit dem Extension-Key `ce_largegallery`.

### Paket installieren

#### Checkout als GIT-Modul

Beispiel in einer _composer_ Installation:

``` bash
$ cd typo3_composer_installation
$ git init
$ git submodule init
$ git submodule add https://github.com/sunixzs/t3ext_ce_largegallery.git public/typo3conf/ext/ce_largegallery
```

Da das Paket nicht offiziell aus dem TER kommt, muss in der `composer.json` noch der Klassenpfad deklariert werden:

```
{
    "autoload": {
        "psr-4": {
            "MAB\\CeLargegallery\\": "public/typo3conf/ext/ce_largegallery/Classes"
        }
    }
}
```

#### Oder direkt als GIT im Extension-Verzeichnis:

Beispiel in einer  _classic_ Installation:

``` bash
$ cd typo3_root/typo3conf/ext
$ git clone https://github.com/sunixzs/t3ext_ce_largegallery.git ce_largegallery
```

#### Oder ganz ohne GIT:

1. ZIP-Datei herunterladen.
1. Paket entpacken.
1. Ordner umbenennen von `t3ext_ce_largegallery` in `ce_largegallery`.
1. Paket wieder komprimmieren. Das Resultat sollte `ce_largegallery.zip` heißen.
1. Im Backend im Extensionmanager das Paket hochladen und installieren.

... oder einfach den Inhalt des Pakets in das Extension-Verzeichnis reinkopieren.

### Installation in Typo3

1. Im Extensionmanager die Extension aktivieren
1. Root-Seite öffnen und `Contentelement Largegallery` zu `Ressourcen -> Seiten-TSconfig` hinzufügen, um den das Inhaltselement bei _Neues Inhaltselement erstellen_ anzuzeigen.
1. Das Template öffnen und `Contentelement Largegallery` zu `Enthält -> Statische Templates einschließen (aus Erweiterungen)` hinzufügen, um die Constants und das Setup zu laden.

> Das statische Template der Extension sollte unbedingt nach `Fluid Content Elements` geladen werden, weil `fluid_styled_content` die view-Einstellungen mit `lib.contentElement >` löscht.

## Konfiguration

### 1. Template-Dateien kopieren

``` bash
$ cd document_root
$ mkdir fileadmin/Resources/Plugins/ce_largegallery
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Private
$ cp -R typo3conf/ext/ce_largegallery/Resources/Private/* fileadmin/Resources/Plugins/ce_largegallery/Private/
```

### 2. CSS/JS kopieren

``` bash
$ cd document_root
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Public
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Public/style
$ mkdir fileadmin/Resources/Plugins/ce_largegallery/Public/script
$ cp typo3conf/ext/ce_largegallery/Resources/Public/style/Largegallery.css fileadmin/Resources/Plugins/ce_largegallery/Public/style/
$ cp typo3conf/ext/ce_largegallery/Resources/Public/script/Largegallery.js fileadmin/Resources/Plugins/ce_largegallery/Public/script/
```

Die CSS- und JS-Dateien werden mit _requirejs_ geladen. Wenn du _requirejs_ nuttzt, passe die Pfade _fileadmin/Resources/Plugins/ce_largegallery/Private/Templates/Largegallery.html_ an:

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

`path/from/require/base/to/` hängt von der require-Konfiguration ab. Das bedeutet, wenn deine Scripts in `fileadmin/Resources/assets/script/` liegen, ist `../../Plugins/ce_largegallery/Public/script/Largegallery` der Pfad.

Die CSS_Datei (`css!...`) wird mit  einem requirejs-Plugin geladen, der gewöhnlich nicht dabei ist.

Wenn du kein _requirejs_ nutzt oder dein eigenes Süppchen kochst oder wie auch immer, lade die Dateien wie sonst auch:

``` typoscript
page.includeCSS.LargeGallery = fileadmin/Resources/Plugins/ce_largegallery/Public/style/Largegallery.css
page.includeJS.LargeGallery = fileadmin/Resources/Plugins/ce_largegallery/Public/script/Largegallery.js
```

Dabei müssen die Wrapper `require()` in der Template-Datei und `define()` in der JS-Datei entfernt werden.

Es gibt eine nicht komprimierte Version in `Resources/Public/script/src/Largegallery.js`.

### 3. TypoScript view-Einstellungen anpassen

``` typoscript
plugin.tx_celargegallery {
    view {
        templateRootPath = fileadmin/Resources/Plugins/ce_largegallery/Private/Templates/
        partialRootPath = fileadmin/Resources/Plugins/ce_largegallery/Private/Partials/
        layoutRootPath = fileadmin/Resources/Plugins/ce_largegallery/Private/Layouts/
    }
}
```

### Anzahl anzuzeigender Daten, Dateiendungen, ...

In [Configuration/TypoScript/constants.typoscript](https://github.com/sunixzs/t3ext_ce_largegallery/blob/master/Configuration/TypoScript/constants.typoscript) gibt es ein paar Einstellungen für:

| Name                  | Typ       | Standardwert  | Beschreibung |
| --------------------- | --------- | ------------- | ----------- |
| imagesOnPageLoad      | integer   | 9             | Anzahl der Bilder, die direkt beim Laden der Seite ausgegeben werden. |
| imagesOnAjaxLoad      | integer   | 12            | Anzahl der Bilder, die jedes Mal beim Klicken auf _Weitere Bilder laden_ per XHR nachgeladen werden. |
| fileExtensionFilter   | string    | jpg,jpeg      | Filter für Dateiendungen, die beim Auslesen des Ordners beachtet werden. |
