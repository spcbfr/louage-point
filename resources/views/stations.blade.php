<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Stations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-3 gap-3">
                        @foreach ($stations as $station)
                            <a class="border-slate-100 hover:bg-slate-50 transition-colors border-2 py-10 flex justify-center items-center  px-2 rounded-md font-medium"
                                href="{{ route('stations.show', ['station' => $station]) }}">
                                {{ $station->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
