"use strict";

/**
 * Create and show a basic modal with given content and options.
 * @param {string} content - The content to be displayed in the modal.
 * @param {Object} [opts] - Optional settings for the modal.
 */
window.appAlert = function(content, opts = {}) {
    // Generate a unique ID for the modal
    const id = `modal_${Math.floor(Date.now() / 1000).toString(16)}${Math.random().toString(16).substr(2, 8)}`;

    // Set default options
    const closable = opts.closable ?? true;
    const title = opts.title ?? false;
    const btnCloseText = opts.btnClose?.text ?? _lang.ok;
    const btnCloseClick = opts.btnClose?.click ?? function () { modal.hide(); };

    // Create modal DOM element
    const dom = document.createElement("div");
    dom.innerHTML = `
        <div id="${id}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden max-w-sm mx-auto w-full p-0 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-auto" x-data="modal">
            <div class="select-none relative w-full h-full max-w-2xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-2xl dark:bg-gray-800">
                    <div class="flex items-start justify-between p-4 rounded-t border-b- border-none dark:border-gray-600" x-show="title !== false">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            ${title}
                        </h3>
                        <button type="button" @click="close" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">${_lang.close}</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            ${content}
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 border-t- border-none border-gray-200 rounded-b dark:border-gray-600">
                        <button type="button" class="btn" @click="close" id="${id}-focusButton">
                            ${btnCloseText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Initialize Alpine data for the modal
    Alpine.data("modal", () => ({
        title: title,
        close() {
            btnCloseClick();
            modal.hide();
        },
    }));

    // Add the modal HTML to the DOM
    document.body.appendChild(dom);

    // Set the target modal element
    const $targetEl = document.getElementById(id);

    // Modal options
    const options = {
        placement: "",
        backdrop: "static",
        closable: closable,
        onHide: () => {
            dom.parentNode.removeChild(dom);
        },
        onShow: () => {
            // Focus on the close button
            document.getElementById(`${id}-focusButton`).focus();
        },
        onToggle: () => {},
    };

    // Create and display the modal
    const modal = new Modal($targetEl, options);
    modal.show();
}

/**
 * Create and display a confirmation modal with the given title, content, and options.
 * @param {string} title - The title to be displayed in the modal.
 * @param {string} content - The content to be displayed in the modal.
 * @param {Object} [opts] - Optional settings for the modal.
 * 
 * appConfirm('Confirm action', 'Are you sure you want to proceed?', {'btnConfirm': {'click': function() { alert(0); }}});
 */
window.appConfirm = function (title, content, opts = {}) {
    // Generate a unique ID for the modal
    const id = `modal_${Math.floor(Date.now() / 1000).toString(16)}${Math.random().toString(16).substr(2, 8)}`;
  
    // Set default options
    const closable = opts.closable ?? true;
    const btnCancelText = opts.btnCancel?.text ?? _lang.cancel;
    const btnConfirmText = opts.btnConfirm?.text ?? _lang.ok;
    const btnConfirmClick = opts.btnConfirm?.click ?? function () { modal.hide(); };
  
    // Create modal DOM element
    const dom = document.createElement("div");
    dom.innerHTML = `
        <div id="${id}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden max-w-sm mx-auto w-full p-0 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-auto" x-data="modal">
            <div class="select-none relative w-full h-full max-w-2xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-2xl dark:bg-gray-800">
                    <div class="flex items-start justify-between p-4 rounded-t border-b- border-none dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            ${title}
                        </h3>
                        <button type="button" x-show="!loading" @click="close" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">${_lang.close}</span>
                        </button>
                        <div role="status" x-show="loading">
                            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            ${content}
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 border-t- border-none border-gray-200 rounded-b dark:border-gray-600">
                        <button type="button" class="btn-primary" @click="confirm" x-bind:disabled="loading" id="${id}-focusButton">
                            ${btnConfirmText}
                        </button>
                        <button type="button" class="btn" @click="close" x-bind:disabled="loading">
                            ${btnCancelText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;

    // Initialize Alpine data for the modal
    Alpine.data("modal", () => ({
        loading: false,
    
        confirm() {
            this.loading = true;
            btnConfirmClick();
            modal.hide();
        },
    
        close() {
            modal.hide();
        },
    }));
    
    // Add the modal HTML to the DOM
    document.body.appendChild(dom);
    
    // Set the target modal element
    const $targetEl = document.getElementById(id);
    
    // Modal options
    const options = {
        placement: "",
        backdrop: "static",
        closable: closable,
        onHide: () => {
            dom.parentNode.removeChild(dom);
        },
        onShow: () => {
            // Focus on the close button
            document.getElementById(`${id}-focusButton`).focus();
        },
        onToggle: () => {},
    };
    
    // Create and display the modal
    const modal = new Modal($targetEl, options);
    modal.show();
};
      
/**
 * Create and display a custom modal with the given title, content, and options.
 * @param {string} title - The title to be displayed in the modal.
 * @param {string} content - The content to be displayed in the modal.
 * @param {Object} [opts] - Optional settings for the modal.
 */
window.appModal = function (title, content, opts) {
    // Implementation needed
};