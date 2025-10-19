<?php
namespace SpellbookGmbhTheme\Helpers;

use DateTime;
use Error;
use Throwable;

/**
 * @since latest
 */
class Utils {
    const CARBON_FIELDS_BLOCK_TYPE_CATEGORY = "carbon-fields";
    
    const PRIMARY_COLOR_HEX = "#3533cd"; // bluish
    const SECONDARY_COLOR_HEX = "#fff";
    const ACCENT_COLOR_HEX = "#000";

    /**
     * @param bool $absolute whether to return an absolute url. Return the full path if `false`. Default is `true`
     * @return string absolute url for assets like styles and scripts
     */
    public static function getAssetUrl(bool $absolute = true): string {
        $path = "/wp-content/themes/spellbook_gmbh_theme/assets";

        return ($absolute ? $_ENV["BASE_URL"] : "") . $path;
    }

    /**
     * @param string $styleSheetName name of the style sheet file (including the extension)
     * @return string assetUrl with the `$styleSheetName` appended
     */
    public static function getStyleSheetUrl(string $styleSheetName): string {
        return Utils::getAssetUrl() . "\/styles/" . ($styleSheetName ?? "");
    }

    /**
     * @param string $scriptName file with extension, no slashes, e.g. "myScript.js"
     * @param bool $absolute whether to return an absolute url. Return the full path if `false`. Default is `true`
     * @return string assetUrl with the `$scriptName` appended
     */
    public static function getScriptUrl(string $scriptName, bool $absolute = true): string {
        return Utils::getAssetUrl($absolute) . "\/script/" . ($scriptName ?? "");
    }

    /**
     * Load either style or script.
     * 
     * @param string $filePath relative to "assets/{script or style}" dir starting with a slash. e.g. "/cfScripts/myScript.js" would be
     * expected to be inside "/assets/script/cfScripts/myScript.js"
     * @param array $scriptStrategy see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
     * @param array $deps see `wp_enqueue` docs
     */
    public static function loadAsset(string $filePath, string|null $scriptStrategy = null, array $deps = []): void {
        Utils::assertNotNullBlankOrThrow($filePath);

        $filePathSplit = explode("/", $filePath);
        $fileName = $filePathSplit[count($filePathSplit) - 1];

        $fileNameSplit = explode(".", $fileName);
        $fileNameWithoutExtension = $fileNameSplit[0]; 
        $fileExtension = $fileNameSplit[1];

        if ($fileExtension === "js")
            wp_enqueue_script(
                $fileNameWithoutExtension,
                Utils::getScriptUrl($filePath, false),
                $deps,
                Utils::getAppVersion(),
                ["strategy" => $scriptStrategy ?? ""]
            );

        else if ($fileExtension === "css")
            wp_enqueue_style(
                $fileNameWithoutExtension,
                Utils::getStyleSheetUrl($filePath, false),
                $deps,
                Utils::getAppVersion(),
            ); 
    }

    public static function getSiteTitle(): string {
        return get_bloginfo('name');
    }

    public static function log(mixed $obj, mixed ...$args): void {
        error_log(print_r($obj, true));

        if ($args)
            foreach ($args as $arg)
                error_log(print_r($arg, true));
    }

    public static function logError(Throwable $error): void {
        if (!$error)
            return;

        Utils::log("Error with code " . $error->getCode() . ": " . $error->getMessage());

        // log stack trace
        if ($error->getTrace())
            Utils::log($error->getTraceAsString());

        // log cause
        if ($error->getPrevious()) {
            Utils::log("Caused by:");
            Utils::logError($error->getPrevious());
        }
    }

    /**
     * Delete all files, subdirectories and subdirectories' files. Don't delete the given dir though.
     * 
     * @param string $dirName absolute path of the dir to clear
     * @return boolean true if all contents have been deleted, else false
     */
    public static function clearDir(string $dirName): bool {
        // case: falsy dir name
        if (Utils::isBlank($dirName) || !is_dir($dirName)) {
            Utils::log("Failed to clear dir. Falsy dirName");
            return false;
        }

        $files = scandir($dirName);

        // case: could not get files
        if (!$files) {
            Utils::log("Failed to clear dir. Failed to get files from dir");
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
                if (Utils::clearDir($file))
                    rmdir($file);

                continue;
            }

            // case: not a file
            if (!is_file($file)) {
                Utils::log("Failed to clear dir. Failed to delete a file");
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
    public static function deleteDir(string $dirName): bool {
        if (Utils::isBlank($dirName) || !is_dir($dirName))
            return false;

        if (Utils::clearDir($dirName))
            return rmdir($dirName);  
        
        return false;
    }

    /**
     * @param string $str to write to fiile
     * @param string $file absolute file name
     * @param int $flags see ```file_put_contents``` for explanation
     * @return bool ```true``` if str has been written to file, else ```false```
     */
    public static function writeStringToFile(string $str, string $file, int $flags = 0): bool {
        // case: falsy params
        if (Utils::isBlank($str)) {
            Utils::log("Failed to write string to file. 'str' is blank");
            return false;
        }

        if (!file_exists($file) || !is_file($file)) {
            Utils::log("Failed to write string to file. Either 'file' " . $file . " could not be found or is a directory");
            return false;
        }

        $bytes = file_put_contents($file, $str);

        return is_numeric($bytes);
    }


    public static function appendStringToFile(string $str, string $file): bool {

        return Utils::writeSTringtoFile($str, $file, FILE_APPEND);
    }


    /**
     * @return bool true if given ```$str``` is falsy, null, not a string or has a size of 0 after trimming it, else false
     */
    public static function isBlank(string|null $str): bool {
        if (!$str)
            return true;

        $trimmedStr = trim($str);

        return strlen($trimmedStr) === 0;
    }

    public static function getTimeStamp(): string {
        $dateTime = new DateTime();
        return $dateTime->format("Y-m-d H:i:s:u");
    }

    /**
     * Throw for the first blank arg but don't throw if no arguments are passed at all
     */
    public static function assertNotNullBlankOrThrow(mixed ...$args): void {
        if (!$args) 
            return;

        for ($i = 0; $i < count($args); $i++) {
            $arg = $args[$i];

            if ($arg == null || (is_string($arg) && Utils::isBlank($arg)))
                throw new Error("Arg $i null or blank");
        }
    }

    /**
     * Same as php's `array_map` but for an associative array.
     * 
     * @param callable $callback mapping function. Params are `$key, $value, $index, $arr`. Should return something
     * @param array $arr to iterate over. If not an assoicative array, `$key` will be the array index
     * @return array non-associative array containing return values of `$callback`
     */
    public static function array_map_key_values(callable $callback, array $arr): array {
        Utils::assertNotNullBlankOrThrow($callback, $arr);
        $resultArr = [];

        $i = 0;
        foreach ($arr as $key => $value) {
            $resultArr[] = call_user_func($callback, $key, $value, $i, $arr);
            $i++;   
        }

        return $resultArr;
    }
    
    public static function getAppVersion(): string {
        return $_ENV["VERSION"];
    }

    public static function getEnv(): string {
        return $_ENV["ENV"];
    }
}