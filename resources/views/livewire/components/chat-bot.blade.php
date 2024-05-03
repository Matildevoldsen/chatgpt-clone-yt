<div class="relative" x-data="{
    text: '',
    temporaryMessage: '',
    sending: false
}">
    <div>
        <ul class="mt-16 space-y-5 min-h-[65vh]">
            @foreach($messages as $message)
                <li class="{{ $message->is_user_message ? 'flex justify-start' : 'flex justify-end' }}">
                    @if ($message->is_user_message)
                        <span class="flex-shrink-0 mr-2 inline-flex items-center justify-center">
                            <img src="{{ auth()->user()->profile_photo_url }}" class="w-8 h-8 rounded-full"
                                 alt="{{ auth()->user()->name }}"/>
                        </span>
                    @endif
                    <div
                        class="message {{ $message->is_user_message ? 'bg-gray-300 text-black max-w-lg rounded-lg' : '!bg-indigo-500 text-white max-w-lg p-3 rounded-lg chat-container' }}">
                        {!! $message->content !!}
                    </div>
                    @if (!$message->is_user_message)
                        <span
                            class="flex-shrink-0 ml-2 inline-flex items-center justify-center size-[38px] rounded-full bg-gray-600">
                            <span class="text-sm font-medium text-white leading-none">AI</span>
                        </span>
                    @endif
                </li>
            @endforeach
            <template x-if="temporaryMessage">
                <li class="flex justify-start">
                    <span class="flex-shrink-0 mr-2 inline-flex items-center justify-center">
                        <img src="{{ auth()->user()->profile_photo_url }}" class="w-8 h-8 rounded-full"
                             alt="{{ auth()->user()->name }}"/>
                    </span>
                    <div class="bg-gray-300 text-black max-w-lg p-3 rounded-lg">
                        <p x-text="temporaryMessage"></p>
                    </div>
                </li>
            </template>
            <template x-if="sending">
                <li class="flex justify-end">
                    <div class="bg-indigo-500 text-white max-w-lg p-3 rounded-lg chat-container" wire:stream="response">
                        {!! $answer !!}
                    </div>
                    <span
                        class="flex-shrink-0 ml-2 inline-flex items-center justify-center size-[38px] rounded-full bg-gray-600">
                        <span class="text-sm font-medium text-white leading-none">AI</span>
                    </span>
                </li>
            </template>
        </ul>
    </div>
    <div class="w-full mx-auto sticky bottom-0 z-10 bg-white border-t border-gray-200 pt-2 pb-4 sm:pt-4 sm:pb-6 px-4 sm:px-6 lg:px-0 dark:bg-neutral-900 dark:border-neutral-700">
        <form class="mt-5 space-y-3" x-on:submit.prevent="
            temporaryMessage = text;
            sending = true;
            $wire.sendMessage(text).then(() => {
                $wire.respond().then(() => {
                    sending = false;
                    temporaryMessage = null;

                    $wire.clearState();
                })
            });
            text = '';
        ">
            <select name="model"
                    id="model"
                    wire:model.live="model"
                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-full text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                <option value="gpt-3.5-turbo" selected="">GPT 3.5</option>
                <option value="gpt-4-turbo-2024-04-09">GPT 4</option>
            </select>
            <textarea name="message"
                      id="message"
                      x-model="text"
                      class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-500"
                      rows="3"></textarea>
            <button type="submit" class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Send
            </button>
        </form>
    </div>
</div>
