<?php ob_start(); ?>
<div
    class="mb-8 border-l-4 border-cp_yellow pl-6 py-4 bg-gradient-to-r from-cp_gray to-transparent relative overflow-hidden">
    <h1 class="text-4xl md:text-5xl font-bold text-white uppercase tracking-wider relative z-10">
        <?= htmlspecialchars($algorithm['name'])?> <span class="text-cp_cyan text-2xl align-top">[MODULE]</span>
    </h1>
    <p class="text-gray-400 mt-2 tracking-widest font-mono relative z-10">Execute
        <?= htmlspecialchars($algorithm['type'])?> algorithm on string target.
    </p>
    <div class="absolute right-0 top-0 h-full w-1/3 bg-cp_yellow opacity-5 skew-x-[-20deg] translate-x-20"></div>
</div>

<div class="bg-cp_gray p-6 md:p-8 cyber-border shadow-lg shadow-cp_red/10 mb-16 relative">
    <div class="absolute top-0 right-0 border-b border-l border-gray-600 p-2 text-xs font-mono text-gray-500">
        ID:
        <?= hash('crc32', $algorithm['slug'])?>
    </div>

    <form method="post" action="" class="space-y-8 mt-2">
        <?php if ($algorithm['type'] === 'hash'): ?>
        <div>
            <label class="block text-cp_yellow text-sm font-bold mb-3 tracking-widest font-mono">> SALT
                [OPTIONAL_PARAMS]</label>
            <input type="text" name="salt" placeholder="Enter salt string..."
                class="cyber-input w-full p-4 text-lg font-mono">
        </div>
        <?php
elseif ($algorithm['type'] === 'encrypt' || $algorithm['type'] === 'decrypt' || $algorithm['type'] === 'sodium_encrypt' || $algorithm['type'] === 'sodium_decrypt'): ?>
        <div>
            <label class="block text-cp_yellow text-sm font-bold mb-3 tracking-widest font-mono">> CRYPTO_KEY
                [REQUIRED]</label>
            <input type="text" name="key" placeholder="Enter secure key (Sodium needs exact length)..."
                class="cyber-input w-full p-4 text-lg font-mono" required>
        </div>
        <div class="mt-4">
            <label class="block text-cp_yellow text-sm font-bold mb-3 tracking-widest font-mono">> INIT_VECTOR / NONCE
                [OPTIONAL/REQUIRED]</label>
            <input type="text" name="iv" placeholder="Enter Initialization Vector or Nonce..."
                class="cyber-input w-full p-4 text-lg font-mono">
        </div>
        <?php
elseif ($algorithm['type'] === 'sodium_hash'): ?>
        <div>
            <label class="block text-cp_yellow text-sm font-bold mb-3 tracking-widest font-mono">> SALT / KEY
                [OPTIONAL_PARAMS]</label>
            <input type="text" name="key" placeholder="Enter optional key..."
                class="cyber-input w-full p-4 text-lg font-mono">
        </div>
        <?php
elseif ($algorithm['name'] === 'password verify'): ?>
        <div>
            <label class="block text-cp_yellow text-sm font-bold mb-3 tracking-widest font-mono">> COMPARE_HASH
                [REQUIRED]</label>
            <input type="text" name="compare_hash" placeholder="Enter hash to verify against..."
                class="cyber-input w-full p-4 text-lg font-mono" required>
        </div>
        <?php
endif; ?>

        <div class="mt-8">
            <label class="block text-cp_cyan text-sm font-bold mb-3 tracking-widest font-mono">>
                <?= $algorithm['name'] === 'password verify' ? 'PASSWORD_STRING' : 'TARGET_STRING'?>
            </label>
            <textarea name="string" rows="4" placeholder="Enter data payload to process..."
                class="cyber-input w-full p-4 text-lg font-mono resize-none"><?= isset($response) ? htmlspecialchars($response) : ''?></textarea>
        </div>

        <button type="submit" class="cyber-btn w-full md:w-auto px-10 py-4 text-lg font-bold">
            EXECUTE_
            <?= strtoupper($algorithm['name'])?> >>
        </button>
    </form>
</div>

<?php if (isset($websiteData['more']) && !empty($websiteData['more'])): ?>
<div class="mt-12">
    <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-800 pb-2 flex items-center gap-3">
        <span class="text-cp_red">■</span> RELATED_MODULES
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <?php foreach ($websiteData['more'] as $key): ?>
        <?php $relatedAlgo = $websiteData['algorithms'][$key]; ?>
        <a href="<?= htmlspecialchars(rtrim($websiteData['url'], '/') . '/algorithm/' . $relatedAlgo['slug'])?>"
            class="block">
            <div class="cyber-card p-4 hover:bg-gray-800">
                <h4 class="text-white font-bold uppercase text-sm tracking-widest">
                    <?= htmlspecialchars($relatedAlgo['name'])?>
                </h4>
            </div>
        </a>
        <?php
    endforeach; ?>
    </div>
</div>
<?php
endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>