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
                    <div class="flex gap-5">
                        <div class="bg-slate-100 px-4 py-4 self-start  rounded-md">
                            <h1 class="text-4xl pr-10 font-bold mb-2">{{ $station->name }}</h1>
                            <a class=" flex gap-1 text-blue-700 font-medium" target="_blank"
                                href="{{ 'https://www.google.com/maps/@' . $station->longitude . ',' . $station->latitude . ',20z' }}">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                Open in Google Maps</a>
                        </div>
                        <div class="space-y-3 flex-grow ">
                            <h1 class="text-xl uppercase tracking-wider font-bold">Possible Destinations</h1>
                            <div class="grid grid-cols-3 gap-3">
                                @forelse ($station->destinations() as $station)
                                    <a class="border-slate-100 hover:bg-slate-50 transition-colors border-2 py-10 flex justify-center items-center  px-2 rounded-md font-medium"
                                        href="{{ route('routes.show', ['route' => $station->routes->sole()->id]) }}">
                                        {{ $station->name }}
                                    </a>
                                @empty
                                    <div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
