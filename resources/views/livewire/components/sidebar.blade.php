<div id="application-sidebar"
     class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700">
    <nav class="hs-accordion-group size-full flex flex-col" data-hs-accordion-always-open>
        <div class="flex items-center justify-between pt-4 pe-4 ps-7">
            <!-- Logo -->
            <h1 class="text-2xl font-mono">{{ config('app.name', 'Laravel') }}</h1>
            <!-- End Logo -->
        </div>

        <div class="h-full">
            <!-- List -->
            <ul class="space-y-1.5 p-4">
                <li wire:click="new">
                    <a class="flex items-center gap-x-3 py-2 px-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-300"
                       href="#">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="M12 5v14"/>
                        </svg>
                        New chat
                    </a>
                </li>
                @foreach($conversations as $conversation)
                    <li>
                        <a href="{{ route('chat.show', $conversation) }}" class="flex items-center gap-x-3 py-2 px-3 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-300">
                            {{ $conversation->title ?? 'New Chat' }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <!-- End List -->
        </div>
    </nav>
</div>
