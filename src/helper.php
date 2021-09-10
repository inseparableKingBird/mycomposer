<?php

use Illuminate\Support\Str;

if (!function_exists('value_if')) {
    function value_if ($first, $default) {
        return $first ?: $default;
    }
}


/**
 * @param string $string
 * @return string
 * @see  https://www.php.net/manual/en/reserved.keywords.php
 */
if (!function_exists('safe_namespace')) {
    function safe_namespace (string $string, $appendStr = 'SafeNamespace'): string {
        $keywords = [
            'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const',
            'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach',
            'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final', 'for', 'foreach', 'function', 'global', 'goto',
            'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace',
            'new', 'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw',
            'trait', 'try', 'unset', 'use', 'var', 'while', 'xor'
        ];

        if (in_array(strtolower($string), $keywords)) {
            return $string.$appendStr;
        }

        return $string;
    }
}


if (!function_exists('full_type_name')) {
    function full_type_name ($type) {
        if (Str::endsWith($type, '[]')) {
            $subType = substr($type, 0, -2);
            if (class_exists($subType)) {
                return '\\'.ltrim($subType, '\\').'[]';
            } else {
                return $type;
            }
        }
        if (class_exists($type)) {
            return '\\'.ltrim($type, '\\');
        }

        return $type;
    }
}

if (!function_exists('str_camel')) {
    function str_camel ($str): string {
        return Str::camel($str);
    }
}

if (!function_exists('var_export_min')) {
    /**
     * dump var
     *
     * @param $var
     * @param bool $return
     * @return mixed|string
     */
    function var_export_min ($var, $return = true): string {
        if (is_array($var)) {
            $toImplode = [];
            foreach ($var as $key => $value) {
                $toImplode[] = var_export($key, true).' => '.var_export_min($value, true);
            }
            $code = '['.implode(',', $toImplode).']';
            if ($return) return $code;
            else echo $code;
        } else {
            // null var_export 会变成 NULL
            if ($var === null) {
                if ($return) return 'null';
                else echo $var;
            }
            return var_export($var, $return);
        }
    }
}

if (!function_exists('del_file_tree')) {
    /**
     * Delete file tree
     *
     * @param $dir
     * @return bool
     */
    function del_file_tree ($dir): bool {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? del_file_tree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}

if (!function_exists('get_files')) {
    /**
     * Get files
     * @param $dir
     * @return array
     */
    function get_files ($dir): array {
        $files = [];
        $scan = scandir($dir);
        foreach ($scan as $item) {
            if ($item == '.' || $item == '..') continue;
            if (is_dir($dir.'/'.$item)) {
                $files = array_merge($files, get_files($dir.'/'.$item));
            } else {
                $files[] = $dir.'/'.$item;
            }
        }
        return $files;
    }
}

if (!function_exists('format_path')) {
    /**
     * Format the relative path as an absolute path with the current working directory as the reference
     *
     * @param $path
     * @return string
     */
    function format_path ($path): string {
        if ($path[0] == ".") {
            $path = getcwd()."/{$path}";
        }

        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = [];
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return "/".implode(DIRECTORY_SEPARATOR, $absolutes);
    }
}

if (!function_exists('safe_url_string')) {
    /**
     * replace the str except [\w,-,.] to '-'
     *
     * @param string $str
     * @return string
     */
    function safe_url_string (string $str): string {
        return preg_replace('/[^\w\-_.]/m', '-', $str);
    }
}
