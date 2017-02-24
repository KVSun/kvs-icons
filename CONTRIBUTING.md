# Contributing to the project

-   [General](#general)
-   [SVG structure](#svg-structure)
-   [Exporting from Illustrator](#exporting-from-illustrator)
-   [Files](#allowed-files)
-   [Tests & linting](#tests-and-linting)
- - -

## General
Write access to the GitHub repository is restricted, so make a fork and clone that. All work should be done on its own branch, named according to the issue number (*e.g. `42` or `bug/23`*). When you are finished with your work, push your feature branch to your fork, preserving branch name (*not to master*), and create a pull request.

**Always pull from `upstream master` prior to sending pull-requests.**

## SVG structure

SVGs **MUST** have `viewBox` attribute. `height` and `width` are optional.  
Where possible, use shapes (`<rect>`, `<circle>` over `<path>`).  
Do not outline text. This makes for larger files and limits what can be done
using CSS.  
Use "websafe" / "system" fonts, if any.

```xml
<svg viewBox="0 0 16 16" width="16" height="16" xmlns="http://www.w3.org/2000/svg" version="1.1">
	<!--`height` and `width` attributes are not required-->
	<path d="..."/>
</svg>
```

## Exporting from Illustrator
[![The different Ways of Getting SVG Out of Adobe Illustrator](https://cdn.css-tricks.com/wp-content/uploads/2016/11/export-svg-options.png)](https://css-tricks.com/illustrator-to-svg/)

See: [How to export SVG](https://helpx.adobe.com/illustrator/how-to/export-svg.html)

-   You may (*but shouldn't*) use "Illustrator Effects", but **not** "Photoshop Effects"
-   Simplify paths: **Object -> Path -> Simplify...**
-   Export: **File > Export... > Format: SVG**
-   Fonts: **Type: SVG**
-   Images: **Makes no difference. `<images>` are not allowed in icons**
-   Make sure to use presentation attributes instead of internal CSS under "Styling"
-   Check both "Minify" & "Responsive"

## Allowed files
All Illustrator (binary) files are stored using Git LFS. All SVG files, being XML, are
stored regularly. All other image formats are ignored and not allowed.

## Tests and linting
All pull requests must pass tests defined in [`svglint.php`](svglint.php) to
be merged into master. This will check that all SVG icons meet basic
standards for use as icons:
-   Root element is an `<svg>`
-   Root element has `viewBox` attribute defined
-   No elements contain `style` attribute
-   Icon does not contain an embedded `<image>`
-   Icon is not an Inkscape SVG (*containing `inkscape:version`*)
