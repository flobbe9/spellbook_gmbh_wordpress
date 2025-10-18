/**
 * Contains non-specific helper functions.
 *
 * @since 0.0.5
 * @author Florin Schikarski
 */

function isBlank(str) {
    return (
        isAnyFalsy(str) || (typeof str === "string" && str.trim().length === 0)
    );
}

/**
 * @param {string} str
 * @returns {boolean} `true` if `str` `isAnyFalsy` AND is a string, else `false`
 */
function isStringFalsy(str) {
    return typeof str === "string" && isAnyFalsy(str);
}

/**
 * @param {any} thing to check
 * @returns {boolean} `true` if, and only if, `thing === null || thing === undefined`;
 */
function isAnyFalsy(thing) {
    return thing === undefined || thing === null;
}

/**
 * @returns ```true``` if the current page is the editor page for an existing post
 */
function isEditPostMode() {
    return (
        window.location.pathname.startsWith("/wp-admin/post.php") &&
        window.location.href.includes("action=edit")
    );
}

/**
 *
 * @returns ```true``` if the current page is the editor page for a new post
 */
function isNewPostMode() {
    return window.location.pathname.startsWith("/wp-admin/post-new.php");
}
