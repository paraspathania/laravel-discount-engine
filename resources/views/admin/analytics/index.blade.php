@extends('layouts.admin')

@section('header', 'Discount Analytics')

@section('content')

    {{-- Filter Panel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8 hover-lift">
        <div class="p-6 bg-slate-950 border-b border-slate-900 flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden">
            <div class="absolute -top-20 -left-20 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <h3 class="text-white font-extrabold flex items-center relative z-10 font-heading text-lg">
                <svg class="w-5 h-5 mr-2.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Reports
            </h3>
            <a href="{{ route('admin.analytics.export', request()->query()) }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all duration-200 flex items-center text-sm shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 hover:-translate-y-0.5 relative z-10">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
        </div>
        <div class="p-6 bg-white/70 backdrop-blur-md">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 font-heading">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold shadow-sm text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 font-heading">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold shadow-sm text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white transition-all duration-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 font-heading">Specific Discount</label>
                    <select name="discount_id" class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold shadow-sm text-sm text-slate-800 bg-slate-50/50 focus:bg-white transition-all duration-200">
                        <option value="">All Discounts</option>
                        @foreach($allDiscounts as $d)
                            <option value="{{ $d->id }}" @selected(request('discount_id') == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-200 hover:-translate-y-0.5">Generate Report</button>
                    @if(request()->hasAny(['from_date','to_date','discount_id']))
                        <a href="{{ route('admin.analytics.index') }}" class="font-bold text-slate-500 hover:text-slate-800 px-4 py-2.5 rounded-xl hover:bg-slate-100 transition-all duration-200 text-sm flex items-center justify-center border border-slate-100">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Metric 1: Total Redemptions -->
        <div class="bg-indigo-600 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-8 text-white shadow-xl shadow-indigo-600/15 relative overflow-hidden group hover-lift" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
            <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-white/10 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center mb-4 relative z-10 backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <p class="text-indigo-100 font-bold uppercase tracking-wider text-xs relative z-10">Total Redemptions</p>
            <h3 class="text-4xl font-black mt-2 relative z-10">{{ number_format($summary['total_redemptions']) }}</h3>
        </div>

        <!-- Metric 2: Total Customer Savings -->
        <div class="bg-emerald-600 bg-gradient-to-br from-emerald-500 to-teal-700 rounded-2xl p-8 text-white shadow-xl shadow-emerald-500/15 relative overflow-hidden group hover-lift" style="background: linear-gradient(135deg, #10b981 0%, #0f766e 100%);">
            <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-white/10 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center mb-4 relative z-10 backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-emerald-100 font-bold uppercase tracking-wider text-xs relative z-10">Total Customer Savings</p>
            <h3 class="text-4xl font-black mt-2 relative z-10">₹{{ number_format($summary['total_saved'] / 100, 2) }}</h3>
        </div>

        <!-- Metric 3: Avg Saved Per Redemption -->
        <div class="bg-purple-600 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl p-8 text-white shadow-xl shadow-purple-600/15 relative overflow-hidden group hover-lift" style="background: linear-gradient(135deg, #9333ea 0%, #db2777 100%);">
            <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-white/10 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center mb-4 relative z-10 backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <p class="text-purple-100 font-bold uppercase tracking-wider text-xs relative z-10">Avg Saved Per Redemption</p>
            <h3 class="text-4xl font-black mt-2 relative z-10 font-heading">₹{{ number_format($summary['avg_saved'] / 100, 2) }}</h3>
        </div>

    </div>

    {{-- Trends & Top Performers Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Trend Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-lift flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 font-heading">Savings & Redemption Trends</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Daily breakdown</p>
                </div>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
        
        {{-- Top Performers list --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-lift flex flex-col justify-between">
            <div class="h-full flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 font-heading mb-5">Top Performing Rules</h3>
                    <ul class="space-y-3.5">
                        @forelse($topDiscounts as $index => $top)
                            <li class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100/50 hover:bg-indigo-50/20 hover:border-indigo-100/30 transition-all duration-200 group">
                                <div class="flex items-center min-w-0 mr-2">
                                    <span class="w-7 h-7 rounded-lg bg-indigo-50 group-hover:bg-indigo-100 text-indigo-600 text-xs font-black flex items-center justify-center shrink-0 mr-3 transition-colors">
                                        #{{ $index + 1 }}
                                    </span>
                                    <span class="text-sm font-bold text-slate-800 truncate group-hover:text-indigo-950 transition-colors">{{ $top->discount->name ?? 'N/A' }}</span>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="block text-xs font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full inline-block mb-0.5">{{ $top->uses }} uses</span>
                                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-slate-500">₹{{ number_format($top->total_saved / 100, 2) }} saved</span>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-slate-400 font-medium py-6 text-center">No performance data yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-slate-100/60 bg-white">
            <h3 class="text-base font-extrabold text-slate-950 font-heading">Redemption Log</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Discount Rule Used</th>
                        <th class="px-6 py-3.5 text-right text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Amount Saved</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($usages as $usage)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-semibold">{{ $usage->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">{{ $usage->order->user->email ?? 'Guest' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $usage->order_id) }}" class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-mono font-bold text-indigo-600 bg-indigo-50 border border-indigo-100/30 hover:bg-indigo-100/50 transition-colors">
                                    #{{ $usage->order_id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-semibold">{{ $usage->discount->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-emerald-600 text-right">₹{{ number_format($usage->saved_amount / 100, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <span class="font-bold text-sm">No analytic data found for this period.</span>
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
            ctx.font = "bold 14px 'Inter', sans-serif";
            ctx.fillStyle = "#94a3b8";
            ctx.textAlign = "center";
            ctx.fillText("No data available for the selected range", ctx.canvas.width/2, ctx.canvas.height/2);
            return;
        }

        // Create gradients
        const usesGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
        usesGradient.addColorStop(0, 'rgba(79, 70, 229, 0.18)');
        usesGradient.addColorStop(1, 'rgba(79, 70, 229, 0.00)');

        const savedGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
        savedGradient.addColorStop(0, 'rgba(16, 185, 129, 0.18)');
        savedGradient.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Redemptions',
                        data: usesData,
                        borderColor: '#6366f1', // indigo-500
                        backgroundColor: usesGradient,
                        yAxisID: 'y',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#6366f1',
                        pointHoverRadius: 8,
                        pointRadius: 4,
                        pointBorderWidth: 2,
                        pointBorderColor: '#ffffff',
                    },
                    {
                        label: 'Savings (₹)',
                        data: savedData,
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: savedGradient,
                        yAxisID: 'y1',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointHoverRadius: 8,
                        pointRadius: 4,
                        pointBorderWidth: 2,
                        pointBorderColor: '#ffffff',
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
                            color: '#94a3b8',
                            font: {
                                family: "'Inter', sans-serif",
                                weight: '600',
                                size: 10
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
                            color: '#6366f1',
                            font: { 
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '800',
                                size: 11
                            }
                        },
                        ticks: {
                            precision: 0,
                            color: '#94a3b8',
                            font: {
                                family: "'Inter', sans-serif",
                                weight: '600',
                                size: 10
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
                            font: { 
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '800',
                                size: 11
                            }
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                family: "'Inter', sans-serif",
                                weight: '600',
                                size: 10
                            }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            boxHeight: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            color: '#475569',
                            font: {
                                family: "'Inter', sans-serif",
                                weight: '600',
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
