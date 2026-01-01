<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>My Claimed Rewards</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body { background-color: #f9fafb; font-family: 'Inter', sans-serif; }
        .reward-item { transition: all 0.2s; }
    </style>
</head>
<body class="min-h-screen">
<div class="max-w-3xl mx-auto p-6">
    <div class="mb-6">
        <a href="{{ url('/reward/customer') }}" class="text-red-600 inline-flex items-center text-sm mb-4">
            <i class="fas fa-arrow-left mr-1"></i> Back to Rewards
        </a>
        <h1 class="text-2xl font-bold text-gray-800">My Claimed Rewards</h1>
        <p class="text-gray-600">All your active discount codes in one place.</p>
    </div>

    <div id="rewardsList" class="space-y-4">
        <!-- Rewards will be inserted here by JS -->
    </div>

    <div id="noRewards" class="text-center py-10 hidden">
        <i class="fas fa-award text-gray-300 text-5xl mb-4"></i>
        <p class="text-gray-500">You haven't claimed any rewards yet.</p>
        <a href="{{ url('/reward/customer') }}" class="mt-4 inline-block text-red-600 font-medium">Browse Rewards</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const rewards = JSON.parse(localStorage.getItem('claimedRewards') || '[]');
    const listEl = document.getElementById('rewardsList');
    const noRewardsEl = document.getElementById('noRewards');

    if (rewards.length === 0) {
        noRewardsEl.classList.remove('hidden');
    } else {
        listEl.innerHTML = rewards.map(reward => `
            <div class="reward-item bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                <div class="flex justify-between">
                    <div>
                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full mb-2">
                            <i class="fas fa-car"></i> ${reward.title}
                        </span>
                        <h3 class="font-bold text-gray-800">${reward.description}</h3>
                        <div class="mt-3">
                            <span class="text-xs font-mono bg-gray-100 px-3 py-2 rounded font-bold">Code: ${reward.code}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Claimed on: ${new Date(reward.claimedAt).toLocaleDateString()}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }
});
</script>
</body>
</html>