<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold">{{ $route->name }} Route</h1>
                    <dl class="grid mt-3 mb-8 gap-4 grid-cols-2  ">
                        <div class="flex flex-col">
                            <dt class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Average Fare</dt>
                            <dd class="font-semibold text-lg">35.000 TND</dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Average Wait Time
                            </dt>
                            <dd class="font-semibold text-lg">
                                {{ $route->calculatedWaitTime ? $route->calculatedWaitTime . ' Minutes' : 'Insufficient Data' }}
                            </dd>
                        </div>

                        <div class="flex flex-col">
                            <dt class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Stations</dt>
                            <dd class="mt-1 text-lg  leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                <ul class="list-disc list-inside">
                                    @foreach ($route->stations as $station)
                                        <li>
                                            <a class=" hover:text-indigo-600 text-indigo-700 font-medium"
                                                href="{{ route('stations.show', ['station' => $station]) }}">
                                                {{ $station->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </dd>
                        </div>
                    </dl>
                    <h2 class="text-2xl font-bold">Recent Trips</h2>
                    <table class="border-collapse mt-3 table-auto w-full text-sm">
                        <thead>
                            <tr>
                                <th
                                    class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    User</th>

                                <th
                                    class="border-b dark:border-slate-600 font-medium p-4 pr-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    Fare</th>
                                <th
                                    class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    Wait Time</th>

                                <th
                                    class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    Group Size</th>

                                <th
                                    class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    Departured At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            @foreach ($route->load('trips')->trips as $trip)
                                <tr>
                                    <td
                                        class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        {{ $trip->user->name }}</td>
                                    <td
                                        class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                        {{ $trip->fare }} TND</td>
                                    <td
                                        class="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">
                                        {{ $trip->wait_time }} minutes</td>

                                    <td
                                        class="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">
                                        {{ $trip->group_size }} {{ Str::plural('person', $trip->group_size) }}</td>

                                    <td
                                        class="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">
                                        {{ $trip->departured_at->format('H:i') }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
