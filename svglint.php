<?php
namespace SVGLint;
/**
 * Recursively lint a directory
 * @param  String   $dir            Directory to lint
 * @param  Array    $exts           Array of extensions to lint in directory
 * @param  Array    $ignore_dirs    Ignore directories in this array
 * @param  Callable $error_callback Callback to call when linting fails
 * @return Bool                     Whether or not all files linted without errors
 * @see https://secure.php.net/manual/en/class.recursiveiteratoriterator.php
 */
use \FilesystemIterator;
use \RecursiveDirectoryIterator as Directory;
use \DomDocument as SVG;
use \DOMElement as Element;
use \DOMNodelist as NodeList;
use \SplFileObject as File;
use \Throwable;
use \Error;

const INVALID_ATTRS = [
	'style',
	['http://www.inkscape.org/namespaces/inkscape', 'version'],
];

const REQUIRED_ATTRS = [
	'viewBox',
];

const INVALID_TAGS = [
	'image',
];

const VERSION = '1.0';
const ENCODING = 'UTF-8';
const EXTS = [
	'svg',
];

function lint_svg(SVG $svg): Bool
{
	has_required_attrs($svg);
	lint_nodes($svg->getElementsByTagName('*'));
	return true;
}

function has_required_attrs(SVG $svg): Bool
{
	$valid = true;
	foreach (REQUIRED_ATTRS as $attr) {
		if (! $svg->documentElement->hasAttribute($attr)) {
			$valid = false;
			Throw new Error("Missing '{$attr}' attribute");
			break;
		}
	}
	return $valid;
}

function lint_nodes(NodeList $nodes): Bool
{
	$valid = true;
	foreach ($nodes as $node) {
		if (! lint_node($node)) {
			$valid = false;
			break;
		}
	}
	return $valid;
}

function lint_node(Element $node): Bool
{
	$valid = true;
	if (in_array($node->tagName, INVALID_TAGS)) {
		throw new Error("Invalid element <{$node->tagName}>");
		$valid = false;
	} else {
		foreach (INVALID_ATTRS as $attr) {
			if (is_string($attr) and $node->hasAttribute($attr)) {
				throw new Error("<{$node->tagName}> has invalid attribute, '{$attr}'");
				$valid = false;
				break;
			} elseif (is_array($attr) and $node->hasAttributeNS($attr[0], $attr[1])) {
				throw new Error("<{$node->tagName}> has invalid attribute, '{$attr[0]}:{$attr[1]}'");
				$valid = false;
				break;
			}
		}
	}
	return $valid;
}

function lint_dir(
	String   $dir            = __DIR__,
	Array    $exts           = EXTS,
	Array    $ignore_dirs    = ['.git', 'node_modules'],
	Callable $error_callback = null
): Bool
{
	$path = new Directory($dir, Directory::SKIP_DOTS);

	while ($path->valid()) {
		if ($path->isFile() and in_array($path->getExtension(), $exts)) {
			try {
				$svg = new SVG(VERSION, ENCODING);
				$svg->load($path->getPathname());
				lint_svg($svg);
			} catch (Throwable $e) {
				echo "{$e->getMessage()} in {$path->getBasename()}" . PHP_EOL;
				return false;
			}
		} elseif ($path->isDir() and ! in_array($path, $ignore_dirs)) {
			// So long as $dir is the first argument of the function, this will
			// always work, even if the name of the function changes.
			$args = array_slice(func_get_args(), 1);
			if (! call_user_func(__FUNCTION__, $path->getPathName(), ...$args)) {
				return false;
			}
		}
		$path->next();
	}
	return true;
}

lint_dir(__DIR__);
