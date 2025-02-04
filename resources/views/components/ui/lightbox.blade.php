<div x-data="{ imgModal: false, imgModalSrc: '', imgModalDesc: '' }">
    <div 
        @img-modal.window="imgModal = true; imgModalSrc = $event.detail.imgModalSrc; imgModalDesc = $event.detail.imgModalDesc;"
        x-show="imgModal" 
        x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" 
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-300" 
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90" 
        x-on:click.away="imgModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 overflow-hidden bg-black bg-opacity-75"
    >
        <div 
            @click.away="imgModal = false" 
            class="flex flex-col max-w-3xl max-h-full overflow-auto"
        >
            <div class="z-50">
                <button @click="imgModal = false" class="float-right pt-2 pr-2 outline-none focus:outline-none">
                    <svg class="w-6 h-6 text-white fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                        <path 
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-2">
                <img :src="imgModalSrc" :alt="imgModalDesc" class="object-contain h-1/2-screen">
                <p x-text="imgModalDesc" class="mt-4 text-center text-white"></p>
            </div>
        </div>
    </div>
</div>
