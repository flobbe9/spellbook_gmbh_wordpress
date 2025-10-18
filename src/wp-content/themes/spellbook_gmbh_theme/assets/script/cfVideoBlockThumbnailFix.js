/**
 * Add missing thumbnails to carbon-fields image blocks with a video source. Blocks with video source somehow
 * loose their thumbnail on page refresh...
 *
 * @since latest
 */

if (isEditPostMode() || isNewPostMode()) observePostAddAllMissingThumbnails();

/**
 * Listens to any change of current post editor.
 */
function observePostAddAllMissingThumbnails() {
    // listen to all post changes
    wp.data.subscribe(() => {
        addAllMissingThumbnails();
    });
}

function addAllMissingThumbnails() {
    Array.from(document.querySelectorAll(".cf-field.cf-image"))
        .filter((cfImageBlock) => isMissingThumbnail(cfImageBlock))
        .forEach((cfImageBlock) => renderMissingThumbnail(cfImageBlock));
}

/**
 * Insert thumbnail (only if missing) into `cfImageBlock`.
 *
 * @param {HTMLDivElement} cfImageBlock the container of the carbon-fields image / video block (class `.cf-field.cf-image`)
 */
function renderMissingThumbnail(cfImageBlock) {
    if (!isMissingThumbnail(cfImageBlock)) return;

    const innerContainer = cfImageBlock.querySelector(".cf-file__inner");
    const hiddenInput = innerContainer.querySelector("input[type=hidden]");
    const src = hiddenInput.value;

    if (!isVideoSource(src))
        return;

    const thumbNail = createThumbnailElement(src);

    // insert between input and button element
    innerContainer.insertBefore(thumbNail, hiddenInput.nextSibling);
}

/**
 * Create a thumbnail div element just like carbon-fields would. Contains the source
 * title and the first video frame.
 * 
 * Only applied to video sources.
 *
 * @param {string} src the source url / file path
 * @return {HTMLDivElement|null} the thumbnail div or `null` if error
 */
function createThumbnailElement(src) {
    if (isBlank(src)) {
        console.warn(
            "Failed to create carbon-fields video thumbnail. 'src' is falsy"
        );
        return null;
    }

    if (!isVideoSource(src))
        return null;

    const fileName = getFileNameBySrcAttribute(src);
    const origin = window.location.origin;

    const containerDiv = document.createElement("div");
    containerDiv.className = "cf-file__content";
    containerDiv.innerHTML = `
        <div class="cf-file__preview">
            <video style="user-select: none; pointer-events: none;" height="100%">
                <source src="${src}" type="video/mp4" />
            </video>

            <button type="button" class="cf-file__remove dashicons-before dashicons-no-alt">
            </button>
        </div>
        <span class="cf-file__name" title="${fileName}">${fileName}</span>`;

    return containerDiv;
}

/**
 * @param {HTMLDivElement} cfImageBlock the container of the carbon-fields image / video block (class `.cf-field.cf-image`)
 * @return {boolean} `true` if container does not have a thumbnail but does have a valid value
 */
function isMissingThumbnail(cfImageBlock) {
    if (!cfImageBlock) return false;

    const hasNoThumbnailElement =
        !cfImageBlock.querySelector(".cf-file__content");
    const hasValidValue = !!cfImageBlock.querySelector(
        ".cf-file__inner input[type=hidden]"
    ).value;

    return hasValidValue && hasNoThumbnailElement;
}

/**
 * Get name of the file or an empty string.
 *
 * @param {string} src the source url / file path
 * @returns {string} the last element after splitting `src` with '/'
 */
function getFileNameBySrcAttribute(src) {
    if (isBlank(src)) return "";

    if (!src.includes("/")) return src;

    const srcParts = src.split("/");

    return srcParts[srcParts.length - 1];
}

/**
 * @param {string} src the source url / file path
 * @returns `true` if the video is in an accepted file format
 */
function isVideoSource(src) {
    if (isBlank(src))
        return false;

    // https://wordpress.com/support/accepted-filetypes/
    const allowedVideoExtensions = [
        ".mp4", 
        ".m4v",
        ".mpg",
        ".mov", 
        ".vtt", 
        ".avi",
        ".ogv", 
        ".wmv", 
        ".3gp", 
        ".3g2", 
    ];

    return !!allowedVideoExtensions
        .find(extension =>
            src.toLowerCase().endsWith(extension.toLowerCase()));
}