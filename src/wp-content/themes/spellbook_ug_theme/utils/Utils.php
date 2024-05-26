<?php


/**
 * Appends url to given style sheet name. 
 * 
* E.g. "styles.css" would become "http://{currentHost}:{currentPort}/wp-content/themes/spellbook_ug_theme/assets/styles/styles.css"
* 
* @param string $styleSheetName name of the style sheet file (including the extension)
* @return string the url appended to the style sheet name
*/
function getStyleSheetUrl(string $styleSheetName): string {
    
    return $_ENV["BASE_URL"] . "/wp-content/themes/spellbook_ug_theme/assets/styles/" . ($styleSheetName ?? "");
}


function getSiteTitle(): string {
    
    return get_bloginfo('name');
}


/**
 * @return true if given url starts with ```$_ENV["BASE_URL"]```
 */
function isUrlInternal(string $url): bool {
    
    if ($url && str_starts_with($url, $_ENV["BASE_URL"]))
        return true;

    return false;
}


function logg(mixed $str, mixed $obj = null): void {

    error_log(print_r($str, true));

    if ($obj)
        error_log(print_r($obj, true));
}


/**
 * Delete all files, subdirectories and subdirectories' files. Don't delete the given dir though.
 * 
 * @param string $dirName absolute path of the dir to clear
 * @return boolean true if all contents have been deleted, else false
 */
function clearDir(string $dirName): bool {

    // case: falsy dir name
    if (isBlank($dirName) || !is_dir($dirName)) {
        logg("Failed to clear dir. Falsy dirName");
        return false;
    }

    $files = scandir($dirName);

    // case: could not get files
    if (!$files) {
        logg("Failed to clear dir. Failed to get files from dir");
        return false;
    }

    // iterate files in dir
    foreach ($files as $file) {
        // avoid these
        if ($file === "." || $file === "..")
            continue;

        $file = $dirName . "/" . $file;

        // case: is dir
        if (is_dir($file)) {
            if (clearDir($file))
                rmdir($file);

            continue;
        }

        // case: not a file
        if (!is_file($file)) {
            logg("Failed to clear dir. Failed to delete a file");
            return false;
        }

        unlink($file);
    }

    return true;
}


/**
 * Delete all files, subdirectories and subdirectories' files.
 * 
 * @param string $dirName absolute path of the dir to clear
 * @return boolean true if all contents have been deleted, else false
 */
function deleteDir(string $dirName): bool {

    if (isBlank($dirName) || !is_dir($dirName))
        return false;

    if (clearDir($dirName))
        return rmdir($dirName);  
    
    return false;
}


/**
 * @return bool true if given ```$str``` is falsy, null, not a string or has a size of 0 after trimming it, else false
 */
function isBlank(string | null $str): bool {

    if (!$str || !is_string($str) || strlen($str) === 0)
        return true;

    $trimmedStr = trim($str);

    return strlen($trimmedStr) === 0;
}