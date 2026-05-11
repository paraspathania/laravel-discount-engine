<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($discount) ? 'Edit Discount: ' . $discount->name : 'Create New Discount' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($discount) ? route('admin.discounts.update', $discount) : route('admin.discounts.store') }}" method="POST" x-data="discountForm()">
                        @csrf
                        @if(isset($discount)) @method('PATCH') @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" value="{{ old('name', $discount->name ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <!-- Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" x-model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed_amount">Fixed Amount</option>
                                    <option value="bogo">Buy One Get One</option>
                                    <option value="free_shipping">Free Shipping</option>
                                </select>
                            </div>

                            <!-- Value -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Value <span x-text="type === 'percentage' ? '(10000 = 100%)' : '(Cents)'"></span></label>
                                <input type="number" name="value" x-model="value" x-on:input="enforcePercentageCap" min="1" value="{{ old('value', $discount->value ?? 100) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :disabled="type === 'bogo' || type === 'free_shipping'">
                            </div>

                            <!-- Priority -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority (Lower = First)</label>
                                <input type="number" name="priority" value="{{ old('priority', $discount->priority ?? 100) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <!-- Dates -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Starts At (Optional)</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($discount) && $discount->starts_at ? $discount->starts_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ends At (Optional)</label>
                                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', isset($discount) && $discount->ends_at ? $discount->ends_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <!-- Limits & Stacking -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Usage Limit (Global)</label>
                                <input type="number" name="usage_limit" value="{{ old('usage_limit', $discount->usage_limit ?? '') }}" placeholder="Leave blank for unlimited" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            
                            <div class="flex items-center mt-6">
                                <input type="hidden" name="is_stackable" value="0">
                                <input type="checkbox" name="is_stackable" value="1" {{ old('is_stackable', $discount->is_stackable ?? false) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-900">Allow stacking with other discounts</label>
                            </div>
                        </div>

                        <!-- Qualifiers -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Qualifiers (Optional - Leave blank for Sitewide)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Specific Products</label>
                                    <select name="product_ids[]" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-32">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', isset($discount) ? $discount->qualifiableProducts->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Specific Categories</label>
                                    <select name="category_ids[]" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-32">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', isset($discount) ? $discount->qualifiableCategories->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.discounts.index') }}" class="bg-gray-200 text-gray-700 py-2 px-4 rounded mr-3 hover:bg-gray-300">Cancel</a>
                            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                                {{ isset($discount) ? 'Update Discount' : 'Create Discount' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- AlpineJS for reactivity (built-in with Breeze) -->
    <script>
        function discountForm() {
            return {
                type: '{{ old('type', $discount->type ?? 'percentage') }}',
                value: '{{ old('value', $discount->value ?? '') }}',
                enforcePercentageCap() {
                    if (this.type === 'percentage' && parseInt(this.value) > 10000) {
                        this.value = 10000;
                    }
                }
            }
        }
    </script>
</x-app-layout>
