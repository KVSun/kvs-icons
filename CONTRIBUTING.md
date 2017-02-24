# Contributing to the project

-   [General](#general)
-   [Tests & linting](#tests-and-linting)
-   [Files](#allowed-files)

- - -

## General
Write access to the GitHub repository is restricted, so make a fork and clone that. All work should be done on its own branch, named according to the issue number (*e.g. `42` or `bug/23`*). When you are finished with your work, push your feature branch to your fork, preserving branch name (*not to master*), and create a pull request.

**Always pull from `upstream master` prior to sending pull-requests.**

## Allowed files
All Illustrator (binary) files are stored using Git LFS. All SVG files, being XML, are
stored regularly. All other image formats are ignored and not allowed.

## Tests and linting
All pull requests must pass tests defined in `svglint.php`. This will check
that all SVG icons meet basic standards for use as icons:
-   Root element is an `<svg>`
-   Root element has `viewBox` attribute defined
-   No elements contain `style` attribute
-   Icon does not contain an embedded `<image>`
-   Icon is not an Inkscape SVG (*containing `inkscape:version`*)
