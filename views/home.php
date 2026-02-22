<?php ob_start(); ?>

<div
    class="mb-12 border-l-4 border-cp_yellow pl-6 py-4 bg-gradient-to-r from-cp_gray to-transparent relative overflow-hidden">
    <h1 class="text-4xl md:text-5xl font-bold text-white uppercase tracking-wider relative z-10">
        System <span class="text-cp_red">Online</span>
    </h1>
    <p class="text-gray-400 mt-2 tracking-widest font-mono relative z-10">Accessing secure cyptographic algorithms...
    </p>
    <div class="absolute right-0 top-0 h-full w-1/3 bg-cp_yellow opacity-5 skew-x-[-20deg] translate-x-20"></div>
</div>

<div class="space-y-4">
    <?php
// Human readable category names
$categoryLabels = [
    'string' => ['name' => 'String Encoders & Decoders', 'icon' => 'fa-font'],
    'hash' => ['name' => 'Standard Hashes', 'icon' => 'fa-hashtag'],
    'password' => ['name' => 'Password Hashing', 'icon' => 'fa-key'],
    'encrypt' => ['name' => 'OpenSSL Encryption', 'icon' => 'fa-lock'],
    'decrypt' => ['name' => 'OpenSSL Decryption', 'icon' => 'fa-lock-open'],
    'sodium_hash' => ['name' => 'Libsodium Hashing', 'icon' => 'fa-shield-halved'],
    'sodium_encrypt' => ['name' => 'Libsodium Encryption', 'icon' => 'fa-shield-cat'],
    'sodium_decrypt' => ['name' => 'Libsodium Decryption', 'icon' => 'fa-shield-heart'],
];
?>

    <?php foreach ($groupedAlgorithms as $type => $algorithms): ?>
    <?php
    $label = $categoryLabels[$type]['name'] ?? strtoupper($type);
    $icon = $categoryLabels[$type]['icon'] ?? 'fa-microchip';
?>

    <!-- Accordion Item utilizing AlpineJS -->
    <div x-data="{ open: false }" class="cyber-border bg-cp_gray overflow-hidden">
        <button @click="open = !open"
            class="w-full px-6 py-4 flex justify-between items-center text-left hover:bg-gray-800 transition-colors duration-200">
            <div class="flex items-center gap-4">
                <i class="fa-solid <?= $icon?> text-cp_cyan text-xl w-8 text-center"></i>
                <h2 class="text-xl font-bold text-white tracking-widest uppercase">
                    <?= htmlspecialchars($label)?> <span class="text-xs text-cp_yellow opacity-70 ml-2">[
                        <?= count($algorithms)?> MODULES]
                    </span>
                </h2>
            </div>
            <!-- Animated Arrow -->
            <i class="fa-solid fa-chevron-down text-cp_yellow transition-transform duration-300"
                :class="{'rotate-180': open}"></i>
        </button>

        <div x-show="open" x-collapse x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-[-10px]" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-[-10px]"
            class="px-6 py-6 border-t border-gray-700 bg-[#0f0f0f]" style="display: none;">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($algorithms as $algorithm): ?>
                <a href="<?= htmlspecialchars(rtrim($websiteData['url'], '/') . '/algorithm/' . $algorithm['slug'])?>"
                    class="block group">
                    <div
                        class="cyber-card p-4 h-full flex flex-col justify-center relative overflow-hidden transition-all duration-300 group-hover:scale-[1.02] group-hover:bg-gray-800">
                        <!-- Glitch effect box on hover -->
                        <div
                            class="absolute inset-0 bg-cp_cyan/10 translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-300 skew-x-[-20deg]">
                        </div>

                        <h4
                            class="text-white font-bold uppercase text-sm tracking-widest relative z-10 flex items-center justify-between">
                            <?= htmlspecialchars($algorithm['name'])?>
                            <i
                                class="fa-solid fa-arrow-right text-xs text-cp_cyan opacity-0 group-hover:opacity-100 transition-opacity transform group-hover:translate-x-1 duration-300"></i>
                        </h4>
                    </div>
                </a>
                <?php
    endforeach; ?>
            </div>

        </div>
    </div>
    <?php
endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>