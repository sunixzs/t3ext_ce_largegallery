---
title: Installation
layout: default
---

# Installation

> Das Paket heißt `t3ext_ce_largegallery` und läuft in Typo3 mit dem Extension-Key `ce_largegallery`.

## Paket installieren

### Checkout als GIT-Modul

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

### Oder direkt als GIT im Extension-Verzeichnis:

Beispiel in einer  _classic_ Installation:

``` bash
$ cd typo3_root/typo3conf/ext
$ git clone https://github.com/sunixzs/t3ext_ce_largegallery.git ce_largegallery
```

### Oder ganz ohne GIT:

1. ZIP-Datei herunterladen.
1. Paket entpacken.
1. Ordner umbenennen von `t3ext_ce_largegallery` in `ce_largegallery`.
1. Paket wieder komprimmieren. Das Resultat sollte `ce_largegallery.zip` heißen.
1. Im Backend im Extensionmanager das Paket hochladen und installieren.

... oder einfach den Inhalt des Pakets in das Extension-Verzeichnis reinkopieren.

## Installation in Typo3

1. Im Extensionmanager die Extension aktivieren
1. Root-Seite öffnen und `Contentelement Largegallery` zu `Ressourcen -> Seiten-TSconfig` hinzufügen, um den das Inhaltselement bei _Neues Inhaltselement erstellen_ anzuzeigen.
1. Das Template öffnen und `Contentelement Largegallery` zu `Enthält -> Statische Templates einschließen (aus Erweiterungen)` hinzufügen, um die Constants und das Setup zu laden.

> Das statische Template der Extension sollte unbedingt nach `Fluid Content Elements` geladen werden, weil `fluid_styled_content` die view-Einstellungen mit `lib.contentElement >` löscht.