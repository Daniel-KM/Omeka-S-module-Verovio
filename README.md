Verovio MEI Viewer (module for Omeka S)
=======================================

> __New versions of this module and support for Omeka S version 3.0 and above
> are available on [GitLab], which seems to respect users and privacy better
> than the previous repository.__

[Verovio MEI Viewer] is a module for [Omeka S] that integrates [Verovio], a
music notation engraving library designed for the Music Encoding Initiative. It
displays [XML-MEI] and [MusicXML] files attached to items, so the visitor can
read musical scores and listen them via MIDI.

For an example, see [Gaffurius codices].


Installation
------------

See general end user documentation for [installing a module].

The module [Common] must be installed first.

The module uses an external library [Verovio], so use the release zip to install
it, or use and init the source.

* Composer (recommended, requires Omeka [pull request #2432])

Install the module from the root of Omeka S:

```sh
composer require daniel-km/omeka-s-module-mirador
```

The module is automatically downloaded in `composer-addons/modules/` and ready
to enable in the admin interface.

* From the zip

Download the last release [Verovio.zip] from the list of releases, and
uncompress it in the `modules` directory. Rename the name of the folder of the
module to `Verovio`

* From the source and for development

If the module was installed from the source, rename the name of the folder of
the module to `Verovio`, go to the root of the module, and run:

```sh
composer install --no-dev
```

* For test

The module includes a comprehensive test suite with unit and functional tests.
Run them from the root of Omeka:

```sh
vendor/bin/phpunit -c modules/Verovio/phpunit.xml --testdox
```

* About Verovio

Verovio is a complex library, but well documented. It is integrated as a simple
viewer ("app") or a full viewer/editor ("toolkit"). The installed version is the
last one.


Usage
-----

### Identification of xml mei files (for old versions of the module)

This issue is now fixed, because the feature is integrated in module [Common],
that is required by this module.

Because mei files are xml files, they are not automatically recognized by Omeka.
To identify them, there are two solutions: use the file extension `.mei` or
install the module [XML Viewer], that identify the xml-mei files with the
unregistered vendor media type `application/vnd.mei+xml`. The point is the same
for the MusicXML files: use extensions `musicxml` or `mxl`. The vendor media
types are managed by the w3c: `application/vnd.recordare.musicxml+xml` (flat)
and `application/vnd.recordare.musicxml` (zipped).

The white lists of media types and extensions are automatically updated to
allow to upload xml files, with the extension and media type above.

### Display of MEI and MusicXML files

When a file has extension `.mei`, `mxl`, or `musicxml`, or media type `application/vnd.mei+xml`
or `application/vnd.recordare.musicxml+xml`, it is automatically displayed anywhere,
in public site or in admin board.

__Warning__: The `mxl` format (zipped musicxml) is not supported by the included
version of Verovio.

__Warning__: The display of the musical scores may be slow on some computers:
Verovio converts xml files into svg in order to display them. For big xml files,
it's recommended to prepare the rendering on the server and to send the svg
file with the xml data.

### Selection of the template of the viewer

A site setting allows to select one of the four default templates to display it:
a simple viewer, the official viewer based on Bootstrap 3, the same viewer based
on Bootstrap 4, and a viewer to be customized.

__Warning__: When the selected template uses jQuery, a js conflict may appear in
the theme in some rare cases. So you may need to enable/disable or defer/undefer
the jQuery library in the template.


### Advanced display in public theme

Because the viewer is integrated as a renderer and not a helper, it is possible
to display it anywhere via the media. In particular, you can use the standard
block "Media" to display it in any page. So just render the media, with possible
options, that are passed directly to the template:

```php
echo $media->render($options);
```

A block layout is available too if needed for external urls. Furthermore, a view
helper is available to render any url anywhere:

```php
echo $this->verovio(null, [
    'source' => 'https://example.org/file.mei',
]);
```

For a better integration in the sites, it's possible to customize the template:
copy file `view/common/verovio-toolkit.phtml` in your theme and update it.


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online issues on the [module issues] page on GitLab.


License
-------

This module is published under the [CeCILL v2.1] license, compatible with
[GNU/GPL] and approved by [FSF] and [OSI].

In consideration of access to the source code and the rights to copy, modify and
redistribute granted by the license, users are provided only with a limited
warranty and the software’s author, the holder of the economic rights, and the
successive licensors only have limited liability.

In this respect, the risks associated with loading, using, modifying and/or
developing or reproducing the software by the user are brought to the user’s
attention, given its Free Software status, which may make it complicated to use,
with the result that its use is reserved for developers and experienced
professionals having in-depth computer knowledge. Users are therefore encouraged
to load and test the suitability of the software as regards their requirements
in conditions enabling the security of their systems and/or data to be ensured
and, more generally, to use and operate it in the same conditions of security.
This Agreement may be freely reproduced and published, provided it is not
altered, and that no provisions are either added or removed herefrom.

[Verovio] is published under the [LGPL] licence.


Copyright
---------

[Verovio]:

* Copyright 2014-2026, Swiss RISM Office

Module Verovio for Omeka S:

* Copyright Daniel Berthereau, 2019-2026

First and next versions of this module was built for [Fachhochschule Nordwestschweiz],
University of Applied Sciences and Arts, Basel Academy of Music, Academy of Music,
[Schola Cantorum Basiliensis].


[Verovio MEI Viewer]: https://gitlab.com/Daniel-KM/Omeka-S-module-Verovio
[Verovio]: https://www.verovio.org
[XML-MEI]: https://music-encoding.org
[MusicXML]: https://w3c.github.io/musicxml/
[Omeka S]: https://omeka.org/s
[Gaffurius codices]: https://www.gaffurius-codices.ch
[pull request #2432]: https://github.com/omeka/omeka-s/pull/2432
[Verovio.zip]: https://gitlab.com/Daniel-KM/Omeka-S-module-Verovio/-/releases
[XML Viewer]: https://gitlab.com/Daniel-KM/Omeka-S-module-XmlViewer
[Bulk Edit]: https://gitlab.com/Daniel-KM/Omeka-S-module-BulkEdit
[module issues]: https://gitlab.com/Daniel-KM/Omeka-S-module-Verovio/-/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[LGPL]: https://www.gnu.org/licenses/lgpl.html
[Fachhochschule Nordwestschweiz]: https://www.fhnw.ch
[Schola Cantorum Basiliensis]: https://www.fhnw.ch/en/about-fhnw/schools/music/schola-cantorum-basiliensis
[GitLab]: https://gitlab.com/Daniel-KM
[Daniel-KM]: https://gitlab.com/Daniel-KM "Daniel Berthereau"
