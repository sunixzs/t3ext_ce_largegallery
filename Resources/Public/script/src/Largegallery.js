/**
 * Provides XHR to load more images for largegallery content element.
 * Also provides a small lightbox.
 */
define(function() {
    /*************************************
     * Lightbox stuff
     *************************************/

    /**
     * The stage for the lightbox all elements are appended to.
     */
    var LightboxStage = function() {
        var s = document.querySelector("body");

        /**
         * Adds an element to stage
         * @param {object} e DOM-element
         */
        this.append = function(e) {
            s.appendChild(e);
        };

        /**
         * Removes an element from stage
         * @param {object} e DOM-element
         */
        this.remove = function(e) {
            if (e) {
                s.removeChild(e);
            }
        };
    };

    /**
     * Handles the needed DOM elements for the Lightbox
     * @param {string} arguments[0] The type of this element.
     * @param {LightboxStage} arguments[1] The stage object this element will be added/removed
     * @param {Lightbox} arguments[2]
     */
    var LightboxElement = function() {
        var elementType = arguments[0],
            stage = arguments[1],
            lightbox = arguments[2],
            element = null; // DOM-element

        var svg = {
            closeButton:
                '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96 96" enable-background="new 0 0 96 96" xml:space="preserve"><polygon points="96,14 82,0 48,34 14,0 0,14 34,48 0,82 14,96 48,62 82,96 96,82 62,48 "/></svg>',
            previousButton:
                '<svg xmlns="http://www.w3.org/2000/svg" width="170.541" height="75.74" viewBox="0 0 170.541 75.74"><g transform="translate(28.737 75.74) rotate(180)"><path d="M158.97,35.026h0a3.226,3.226,0,0,0-.987-2.27L120.089-1.791a3.384,3.384,0,0,0-3.464-.486A3,3,0,0,0,114.754.582V22.69H-8.4a3.124,3.124,0,0,0-3.169,3.154V44.8A3.124,3.124,0,0,0-8.4,47.952H114.754V70.06a3.162,3.162,0,0,0,5.335,2.27L157.982,37.4a3.471,3.471,0,0,0,.987-2.373" transform="translate(-130.232 2.549)" /></g></svg>',
            nextButton:
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 170.541 75.74"><path d="M158.97,35.026h0a3.226,3.226,0,0,0-.987-2.27L120.089-1.791a3.384,3.384,0,0,0-3.464-.486A3,3,0,0,0,114.754.582V22.69H-8.4a3.124,3.124,0,0,0-3.169,3.154V44.8A3.124,3.124,0,0,0-8.4,47.952H114.754V70.06a3.162,3.162,0,0,0,5.335,2.27L157.982,37.4a3.471,3.471,0,0,0,.987-2.373" transform="translate(11.572 2.549)" /></svg>'
        };

        /**
         * Create the DOM-element
         * @param {string} arguments[0] class attribute
         * @param {string} arguments[1] innerHTML
         * @param {function} arguments[2] an onclick callback
         * @param {string} arguments[3] title attribute
         */
        var createElement = function() {
            element = document.createElement("DIV");

            if (arguments[0]) {
                element.setAttribute("class", arguments[0]);
            }

            if (arguments[1]) {
                element.innerHTML = arguments[1];
            }

            if (arguments[2]) {
                element.addEventListener("click", arguments[2], false);
            }

            if (arguments[3]) {
                element.setAttribute("title", arguments[3]);
            }

            stage.append(element);
        };

        /**
         * Creates the image container with the 'currentImage' as background
         */
        this.init = function() {
            // return element early if it is allready created
            if (element) {
                return element;
            }

            // create or update element
            switch (elementType) {
                case "background":
                    createElement("ce-largegallery__lightbox--background", "", function() {
                        lightbox.close();
                    });
                    break;
                case "closeButton":
                    createElement(
                        "ce-largegallery__lightbox--close",
                        svg.closeButton,
                        function() {
                            lightbox.close();
                        },
                        "Lightbox schließen (ESC-Taste)"
                    );
                    break;
                case "previousButton":
                    createElement(
                        "ce-largegallery__lightbox--previous",
                        svg.previousButton,
                        function() {
                            lightbox.showPrevious();
                        },
                        "Vorheriges Bild (Pfeil-Taste links)"
                    );
                    break;
                case "nextButton":
                    createElement(
                        "ce-largegallery__lightbox--next",
                        svg.nextButton,
                        function() {
                            lightbox.showNext();
                        },
                        "Nächstes Bild (Pfeil-Taste rechts)"
                    );
                    break;
                case "container":
                    createElement("ce-largegallery__lightbox--container");
                    break;
            }

            return element;
        };

        /**
         * removes the element from stage
         */
        this.remove = function() {
            stage.remove(element);
            element = null;
        };
    };

    var Lightbox = function() {
        var _self = this;
        var currentImage = 0,
            images = [],
            isOpened = false;

        // DOM stuff
        var stage = new LightboxStage(),
            background = new LightboxElement("background", stage, this),
            closeButton = new LightboxElement("closeButton", stage, this),
            container = new LightboxElement("container", stage, this),
            previousButton = new LightboxElement("previousButton", stage, this),
            nextButton = new LightboxElement("nextButton", stage, this);

        /**
         * @param {string} imageUrl
         * @returns {number} pointer in images array
         */
        this.addImage = function(imageUrl) {
            images.push(imageUrl);
            return images.length - 1;
        };

        /**
         * Shows the current image after the lightbox was opened.
         */
        this.show = function() {
            if (!images[currentImage]) {
                console.warn("Could not find image " + currentImage + " in images!");
                return;
            }
            var e = container.init();
            e.style.backgroundImage = "url(" + images[currentImage] + ")";
            handleButtons();
        };

        /**
         * shows the next image
         */
        this.showNext = function() {
            if (images[currentImage + 1]) {
                currentImage++;
                this.show();
            }
        };

        /**
         * shows the previous image
         */
        this.showPrevious = function() {
            if (images[currentImage - 1]) {
                currentImage--;
                this.show();
            }
        };

        /**
         * sets the button visibility
         */
        var handleButtons = function() {
            if (images[currentImage - 1]) {
                previousButton.init();
            } else {
                previousButton.remove();
            }

            if (images[currentImage + 1]) {
                nextButton.init();
            } else {
                nextButton.remove();
            }
        };

        /**
         * Removes all elements from stage
         */
        this.close = function() {
            background.remove();
            closeButton.remove();
            container.remove();
            previousButton.remove();
            nextButton.remove();
            isOpened = false;
        };

        /**
         * opens an image in the lightbox.
         * @param {number} n Pointer in images array.
         */
        this.open = function(n) {
            if (!images[n]) {
                return;
            }
            currentImage = n;

            background.init();
            closeButton.init();
            this.show();
            isOpened = true;
        };

        /**
         * bind esc, left and right keys
         */
        document.addEventListener(
            "keydown",
            function(evt) {
                if (!isOpened) {
                    return false;
                }

                evt = evt || window.event;
                var isEscape = false;
                var isPrevious = false;
                var isNext = false;

                if ("key" in evt) {
                    isEscape = evt.key === "Escape" || evt.key === "Esc";
                    isPrevious = evt.key === "ArrowLeft";
                    isNext = evt.key === "ArrowRight";
                } else {
                    isEscape = evt.keyCode === 27;
                    isPrevious = evt.keyCode === 37;
                    isNext = evt.keyCode === 39;
                }

                if (isEscape) {
                    _self.close();
                } else if (isPrevious) {
                    if (images[currentImage - 1]) {
                        currentImage--;
                        _self.show();
                    }
                } else if (isNext) {
                    if (images[currentImage + 1]) {
                        currentImage++;
                        _self.show();
                    }
                }
            },
            false
        );
    };

    /*************************************
     * Main class for XHR and Lightbox initialization
     *************************************/

    /**
     *
     */
    var Largegallery = function(params) {
        var container = params.container;
        var entryPoint = params.entryPoint;
        var offset = params.offset;
        var key = params.key;
        var xhrButton = params.xhrButton;
        var request = null;

        /**
         *
         */
        var initLightbox = function() {
            var LB = new Lightbox();
            var elements = container.querySelectorAll("[data-image-url]");
            for (var e = 0; e < elements.length; e++) {
                var imageNum = LB.addImage(elements[e].getAttribute("data-image-url"));
                elements[e].setAttribute("data-image-num", imageNum);
                elements[e].addEventListener(
                    "click",
                    function(evt) {
                        evt.preventDefault();
                        LB.open(parseInt(this.getAttribute("data-image-num")));
                    },
                    false
                );
            }
        };

        initLightbox();

        /**
         * Merges an object to a query string.
         * @param {object} params 
         */
        var formatParams = function(params) {
            return (
                (entryPoint.indexOf("?") > -1 ? "&" : "?") +
                Object.keys(params)
                    .map(function(key) {
                        return key + "=" + encodeURIComponent(params[key]);
                    })
                    .join("&")
            );
        };

        /**
         * Callback after a XHR request.
         * @param {object} data 
         */
        var callback = function (data) {
            if (data && data.content) {
                // add content
                container.innerHTML += data.content;
                initLightbox();

                if (data.total > data.end) {
                    // there is more to load
                    offset = data.offset;
                    xhrButton.parentNode.style.visibility = "visible";
                } else {
                    // end of the list - we need no ajax link anymore
                    xhrButton.parentNode.parentNode.removeChild(xhrButton.parentNode);
                }
            } else {
                // something went wrong - remove ajax link
                xhrButton.parentNode.parentNode.removeChild(xhrButton.parentNode);
            }
        }

        // bind button to load more images
        xhrButton.addEventListener(
            "click",
            function(evt) {
                evt.preventDefault();

                // show loading
                container.style.cursor = "wait";

                // hide link
                xhrButton.parentNode.style.visibility = "hidden";

                // build xhr params
                var params = {
                    type: 19845,
                    "tx_celargegallery_pi1[controller]": "Gallery",
                    "tx_celargegallery_pi1[action]": "xhr",
                    "tx_celargegallery_pi1[offset]": offset,
                    "tx_celargegallery_pi1[key]": key
                };

                // send xhr
                request = new XMLHttpRequest();
                request.overrideMimeType("application/json");

                request.addEventListener("load", function(evt) {
                    if (request.status >= 200 && request.status < 300) {
                        //console.log(request.responseText);
                        var responseValue = JSON.parse(request.responseText);
                        callback(responseValue);

                        // reset loading style
                        container.style.cursor = "auto";
                    } else {
                        console.warn(request.statusText, request.responseText);
                    }
                });
                request.open("GET", entryPoint + formatParams(params));
                request.send();
            },
            false
        );
    };

    return Largegallery;
});
