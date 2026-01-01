<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Staff - Manage Loyalty Rewards</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hidden { display: none; }
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen">

<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="text-sm text-gray-500 mb-1">Staff Panel / Loyalty</div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Loyalty Rewards</h1>
        <p class="text-gray-600">Create or update referral rewards for customer stamp collection.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
        <form id="rewardForm">
            <!-- Reward Type Toggle -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reward Type</label>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="reward_type" value="discount" checked class="sr-only peer" onclick="toggleFields()">
                        <div class="w-5 h-5 rounded-full border border-gray-300 peer-checked:bg-red-600 peer-checked:border-red-600 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                        </div>
                        <span class="ml-2 text-gray-700">Discount (%)</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="reward_type" value="extra_hours" class="sr-only peer" onclick="toggleFields()">
                        <div class="w-5 h-5 rounded-full border border-gray-300 peer-checked:bg-red-600 peer-checked:border-red-600 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                        </div>
                        <span class="ml-2 text-gray-700">Extra Rental Hours</span>
                    </label>
                </div>
            </div>

            <!-- Discount Input -->
            <div id="discount-field" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Discount Amount (%)</label>
                <input
                    type="number"
                    name="discount"
                    min="0"
                    max="20"
                    step="0.1"
                    value="10"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none"
                    placeholder="e.g. 15"
                />
                <p class="mt-1 text-xs text-gray-500">Max: 20%. Must be ≥ 0.</p>
            </div>

            <!-- Extra Hours Input -->
            <div id="hours-field" class="mb-6 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Extra Rental Hours</label>
                <input
                    type="number"
                    name="extra_hours"
                    min="0"
                    max="2"
                    step="0.1"
                    value="1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none"
                    placeholder="e.g. 1.5"
                />
                <p class="mt-1 text-xs text-gray-500">Max: 2 hours. Must be ≥ 0.</p>
            </div>

            <!-- Stamps Required -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Stamps Required to Claim</label>
                <input
                    type="number"
                    name="stamps_required"
                    min="1"
                    value="5"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none"
                />
                <p class="mt-1 text-xs text-gray-500">Minimum: 1 stamp.</p>
            </div>

            <!-- Max Claims -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Max Customers Allowed to Claim</label>
                <input
                    type="number"
                    name="max_claims"
                    min="1"
                    max="10"
                    value="10"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none"
                />
                <p class="mt-1 text-xs text-gray-500">System limit: 10 customers per referral code.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-5 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Save Reward
                </button>
            </div>
        </form>
    </div>

    <!-- Business Rules -->
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <p class="text-sm text-gray-700">
            <span class="font-bold">Business Rules:</span> 
            Discount ≤ 20%, Extra Hours ≤ 2, Max Claims = 10, No negative values allowed.
        </p>
    </div>
</div>

<script>
function toggleFields() {
    const discountField = document.getElementById('discount-field');
    const hoursField = document.getElementById('hours-field');
    const discountChecked = document.querySelector('input[name="reward_type"][value="discount"]').checked;

    if (discountChecked) {
        discountField.classList.remove('hidden');
        hoursField.classList.add('hidden');
    } else {
        discountField.classList.add('hidden');
        hoursField.classList.remove('hidden');
    }
}

document.getElementById('rewardForm').addEventListener('submit', (e) => {
    e.preventDefault();
    alert('✅ Reward saved!\n(A static preview – no data stored.)');
});
</script>

</body>
</html>