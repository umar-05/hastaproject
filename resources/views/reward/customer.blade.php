<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Loyalty Rewards</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body { background-color: #f9fafb; font-family: 'Inter', sans-serif; }
        .stamp { 
            width: 44px; height: 44px; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: bold; border: 2px solid #e5e7eb; border-radius: 50%; 
            font-size: 14px;
        }
        .stamp.filled { 
            background-color: #ef4444; color: white; border-color: #ef4444; 
        }
        .reward-card { transition: all 0.2s; }
        .reward-card.unavailable { opacity: 0.7; }
    </style>
</head>
<body class="min-h-screen">

<div class="max-w-3xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Loyalty Rewards</h1>
        <p class="text-gray-600">
            Earn 1 stamp for every rental of <strong>10+ hours</strong>.<br>
            Every <strong>3 stamps</strong> = 1 redeemable reward!
        </p>
    </div>

    <!-- Stamps Progress -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-medium text-gray-800">Your Stamps</h2>
            <span class="text-lg font-bold text-red-600">5 Stamps</span>
        </div>
        
        <!-- Stamp Visualization (show up to 9 for clarity) -->
        <div class="flex flex-wrap gap-3 mb-4">
            <!-- Stamps are NOT consumed — just show total -->
            <div class="stamp filled">1</div>
            <div class="stamp filled">2</div>
            <div class="stamp filled">3</div>
            <div class="stamp filled">4</div>
            <div class="stamp filled">5</div>
            <!-- Empty for future -->
            <div class="stamp">6</div>
            <div class="stamp">7</div>
            <div class="stamp">8</div>
            <div class="stamp">9</div>
        </div>

        <!-- Reward Eligibility -->
        <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                <div>
                    <p class="text-sm text-gray-700">
                        You have <strong>5 stamps</strong> → <strong>1 reward(s)</strong> available to claim!
                        <br><span class="font-medium">Unclaimed rewards stay available.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Claimable Rewards Section -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Available Rewards</h2>
        
<!-- Reward 1: Claimable -->
<div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 mb-4 reward-card">
    <div class="flex justify-between">
        <div>
            <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full mb-2">
                <i class="fas fa-car"></i> Car Rental Discount
            </span>
            <h3 class="font-bold text-gray-800">15% Off Your Next Car Rental</h3>
            <p class="text-sm text-gray-600 mt-1">
                Apply this code during checkout to get discount on your rental.
            </p>
        </div>
        <div class="text-right">
            <span class="text-sm text-gray-500 block mb-2">Requires: 3 stamps</span>
            <button 
                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700"
                onclick="claimReward('Car Rental Discount', '15% Off Your Next Car Rental', 'RENTAL-15P-8A3B')"
            >
                Claim Reward
            </button>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="rewardModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-center">
        <div class="text-green-500 text-4xl mb-4">
            <i class="fas fa-gift"></i>
        </div>
        <h3 class="font-bold text-lg text-gray-800 mb-2">Reward Claimed!</h3>
        <p class="text-gray-600 text-sm mb-4">
            Your code: <br>
            <span class="font-mono font-bold bg-gray-100 px-2 py-1 rounded inline-block mt-1" id="modalCode"></span>
        </p>
        <div class="flex flex-col gap-2">
            <button 
                onclick="goToMyRewards()"
                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700"
            >
                View All My Rewards
            </button>
            <button 
                onclick="document.getElementById('rewardModal').classList.add('hidden')"
                class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg"
            >
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Simulate saving claimed rewards in browser storage
function claimReward(title, description, code) {
    // Get existing rewards
    let claimed = JSON.parse(localStorage.getItem('claimedRewards') || '[]');
    
    // Avoid duplicates
    const exists = claimed.some(r => r.code === code);
    if (!exists) {
        claimed.push({ title, description, code, claimedAt: new Date().toISOString() });
        localStorage.setItem('claimedRewards', JSON.stringify(claimed));
    }

    // Show modal
    document.getElementById('modalCode').textContent = code;
    document.getElementById('rewardModal').classList.remove('hidden');
}

function goToMyRewards() {
    window.location.href = "{{ route('rewards.claimed') }}";
}
</script>

<script>
function showRewardModal(code) {
    document.getElementById('modalCode').textContent = code;
    document.getElementById('rewardModal').classList.remove('hidden');
    navigator.clipboard.writeText(code).then(() => {
        // Optional: show "Copied!" briefly
    });
}
</script>

        <!-- Reward 2: Not Yet Eligible (Need 6 stamps for 2nd reward) -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 reward-card unavailable">
            <div class="flex justify-between">
                <div>
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mb-2">
                        <i class="fas fa-clock"></i> Extra Rental Hours
                    </span>
                    <h3 class="font-bold text-gray-800">+1.5 Free Hours on Next Rental</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Apply this code during checkout to extend your rental.
                    </p>
                    <div class="mt-2">
                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-400">RENTAL-1H30-XXXX</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500 block mb-2">Requires: 6 stamps</span>
                    <button 
                        class="px-4 py-2 bg-gray-300 text-gray-500 text-sm rounded-lg cursor-not-allowed"
                        disabled
                    >
                        Need 1 More Stamp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-gray-50 rounded-xl p-5 border">
        <h3 class="font-semibold text-gray-800 mb-3">How Loyalty Rewards Work</h3>
        <ul class="text-sm text-gray-600 space-y-2">
            <li class="flex items-start">
                <span class="text-red-500 mr-2">•</span>
                <span>Rent a vehicle for <strong>10+ hours</strong> → earn <strong>1 stamp</strong>.</span>
            </li>
            <li class="flex items-start">
                <span class="text-red-500 mr-2">•</span>
                <span>Every <strong>3 stamps</strong> = <strong>1 reward</strong> (discount or extra hours).</span>
            </li>
            <li class="flex items-start">
                <span class="text-red-500 mr-2">•</span>
                <span>Stamps <strong>never expire</strong> and <strong>are not deducted</strong> when you claim.</span>
            </li>
            <li class="flex items-start">
                <span class="text-red-500 mr-2">•</span>
                <span>Unclaimed rewards stay available until you redeem them.</span>
            </li>
        </ul>
    </div>
</div>

</body>
</html>