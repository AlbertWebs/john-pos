@extends('layouts.app')

@section('title', 'Vehicle Make Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('vehicle-makes.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Vehicle Makes
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $vehicleMake->make_name }}</h1>
            </div>
            <a href="{{ route('vehicle-makes.edit', $vehicleMake) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="space-y-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Make Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make Name</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $vehicleMake->make_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Models Count</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $vehicleMake->vehicleModels->count() }} models
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            @if($vehicleMake->vehicleModels->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Models</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year Range</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vehicleMake->vehicleModels as $model)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $model->model_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    @if($model->year_start && $model->year_end)
                                        {{ $model->year_start }}-{{ $model->year_end }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

