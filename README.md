Verovio MEI Viewer (module for Omeka S)
=======================================

[Verovio MEI Viewer] is a module for [Omeka S] that integrates [Verovio], a
music notation engraving library designed for the Music Encoding Initiative. It
displays [XML-MEI] files attached to items, so the visitor can read musical
scores and listen them via MIDI.


Installation
------------

The module uses an external js library [Verovio], so use the release zip to
install it, or use and init the source.

* From the zip

Download the last release [`Verovio.zip`] from the list of releases (the
master does not contain the dependency), and uncompress it in the `modules`
directory.

* From the source and for development:

If the module was installed from the source, rename the name of the folder of
the module to `Verovio`, and go to the root module, and run:

```
    composer install
```

The next times:

```
    composer update
```

Then install it like any other Omeka module.


Usage
-----

### Identification of xml mei files

Because mei files are xml files, they are not automatically recognized by Omeka.
To identify them, there are two solutions: use the file extension `.mei` or
install the module [Next], that identify the xml-mei files with the unregistered
 vendor media type `application/vnd.mei+xml`.

The white lists of media types and extensions are automatically updated to
allow to upload xml files, with the extension and media type above.

### Display of MEI files

When a file has extension `.mei` or media type `application/vnd.mei+xml`, it is
automatically displayed anywhere, in public site or in admin board.


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online issues on the [module issues] page on GitHub.


License
-------

This module is published under the [CeCILL v2.1] licence, compatible with
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

* Copyright 2014-2019, Swiss RISM Office

Module Verovio for Omeka S:

* Copyright Daniel Berthereau, 2019

First version of this module was built for [Fachhochschule Nordwestschweiz],
University of Applied Sciences and Arts, Basel Academy of Music, Academy of Music,
[Schola Cantorum Basiliensis].


[Verovio MEI Viewer]: https://github.com/Daniel-KM/Omeka-S-module-Verovio
[Verovio]: https://www.verovio.org
[XML-MEI]: https://music-encoding.org
[Omeka S]: https://omeka.org/s
[`Verovio.zip`]: https://github.com/Daniel-KM/Omeka-S-module-Verovio/releases
[Next]: https://github.com/Daniel-KM/Omeka-S-module-Next
[module issues]: https://github.com/Daniel-KM/Omeka-S-module-Verovio/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[LGPL]: https://www.gnu.org/licenses/lgpl.html
[Fachhochschule Nordwestschweiz]: https://www.fhnw.ch
[Schola Cantorum Basiliensis]: https://www.fhnw.ch/en/about-fhnw/schools/music/schola-cantorum-basiliensis
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
