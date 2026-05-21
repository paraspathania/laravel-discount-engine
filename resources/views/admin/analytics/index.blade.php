@extends('layouts.admin')

@section('header', 'Discount Analytics')

@section('content')

    {{-- Filter Panel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-6 bg-slate-900 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-white font-extrabold flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Reports
            </h3>
            <a href="{{ route('admin.analytics.export', request()->query()) }}"
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
        </div>
        <div class="p-6 bg-white">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Specific Discount</label>
                    <select name="discount_id" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm">
                        <option value="">All Discounts</option>
                        @foreach($allDiscounts as $d)
                            <option value="{{ $d->id }}" @selected(request('discount_id') == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2.5 px-6 rounded-xl shadow-md transition-colors">Generate Report</button>
                    @if(request()->hasAny(['from_date','to_date','discount_id']))
                        <a href="{{ route('admin.analytics.index') }}" class="font-bold text-gray-500 hover:text-gray-800 px-3 py-2.5 rounded-xl hover:bg-gray-100 transition-colors text-sm">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
            <p class="text-indigo-100 font-bold uppercase tracking-wider text-sm mb-1">Total Redemptions</p>
            <h3 class="text-5xl font-black">{{ $summary['total_redemptions'] }}</h3>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-700 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
            <p class="text-green-100 font-bold uppercase tracking-wider text-sm mb-1">Total Customer Savings</p>
            <h3 class="text-5xl font-black">₹{{ number_format($summary['total_saved'] / 100, 2) }}</h3>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden flex flex-col justify-center">
            <div class="p-3.5 bg-yellow-50 text-yellow-600 rounded-xl w-fit mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <p class="text-gray-500 font-bold uppercase tracking-wider text-xs mb-1">Avg Saved Per Redemption</p>
            <h3 class="text-3xl font-black text-gray-900">₹{{ number_format($summary['avg_saved'] / 100, 2) }}</h3>
        </div>
    </div>

    {{-- Trends & Top Performers Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Trend Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-extrabold text-gray-900">Savings & Redemption Trends</h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Daily breakdown</span>
            </div>
            <div class="relative h-80">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
        
        {{-- Top Performers list --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-gray-900 mb-4">Top Performing Rules</h3>
                <ul class="space-y-4">
                    @forelse($topDiscounts as $index => $top)
                        <li class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center min-w-0 mr-2">
                                <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex items-center justify-center shrink-0 mr-3">#{{ $index + 1 }}</span>
                                <span class="text-sm font-bold text-gray-800 truncate">{{ $top->discount->name ?? 'N/A' }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="block text-xs font-black text-indigo-600">{{ $top->uses }} uses</span>
                                <span class="block text-[10px] font-bold text-gray-400">₹{{ number_format($top->total_saved / 100, 2) }} saved</span>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-400 font-medium">No performance data yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Detailed Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Discount Rule Used</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount Saved</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($usages as $usage)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $usage->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $usage->order->user->email ?? 'Guest' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $usage->order_id) }}" class="text-sm font-mono text-indigo-600 font-bold hover:underline">#{{ $usage->order_id }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">{{ $usage->discount->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-green-600 text-right">₹{{ number_format($usage->saved_amount / 100, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="font-bold">No analytic data found for this period.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($usages, 'links') && $usages->hasPages())
        <div class="mt-6">
            {{ $usages->links() }}
        </div>
    @endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('trendsChart').getContext('2d');
        
        const labels = {!! json_encode($chartLabels) !!};
        const usesData = {!! json_encode($chartUses) !!};
        const savedData = {!! json_encode($chartSaved) !!};

        if (labels.length === 0) {
            ctx.font = "16px sans-serif";
            ctx.fillStyle = "#9ca3af";
            ctx.textAlign = "center";
            ctx.fillText("No data available for the selected range", ctx.canvas.width/2, ctx.canvas.height/2);
            return;
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Redemptions',
                        data: usesData,
                        borderColor: '#4f46e5', // indigo-600
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        yAxisID: 'y',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#4f46e5',
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Savings (₹)',
                        data: savedData,
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        yAxisID: 'y1',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Redemptions',
                            color: '#4f46e5',
                            font: { weight: 'bold' }
                        },
                        ticks: {
                            precision: 0,
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Savings (₹)',
                            color: '#10b981',
                            font: { weight: 'bold' }
                        },
                        ticks: {
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(243, 244, 246, 1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 15,
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
