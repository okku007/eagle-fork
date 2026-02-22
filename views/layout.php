<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($websiteData['title'] ?? 'Project Eagle')?>
    </title>
    <meta name="description" content="<?= htmlspecialchars($websiteData['description'] ?? '')?>">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- AlpineJS for interactive components (Accordions) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        cp_yellow: '#FCEE0A',
                        cp_red: '#FF003C',
                        cp_cyan: '#00F0FF',
                        cp_dark: '#0a0a0a',
                        cp_gray: '#1f1f1f'
                    },
                    fontFamily: {
                        cyber: ['Rajdhani', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Rajdhani', sans-serif;
            background-color: #0a0a0a;
            color: #fff;
        }

        .cyber-border {
            position: relative;
        }

        .cyber-border::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 15px;
            height: 15px;
            background-color: #0a0a0a;
            clip-path: polygon(100% 0, 0 100%, 100% 100%);
        }

        .cyber-btn {
            background-color: #FCEE0A;
            color: #0a0a0a;
            position: relative;
            clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);
            transition: all 0.2s ease-in-out;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cyber-btn:hover {
            background-color: #00F0FF;
        }

        .cyber-card {
            background-color: #1f1f1f;
            border-left: 4px solid #FCEE0A;
            transition: all 0.2s ease-in-out;
            clip-path: polygon(0 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%);
        }

        .cyber-card:hover {
            border-left-color: #00F0FF;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.3);
            transform: translateY(-2px);
        }

        .cyber-input {
            background-color: #1a1a1a;
            border: 1px solid #333;
            color: #00F0FF;
            clip-path: polygon(0 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%);
        }

        .cyber-input:focus {
            outline: none;
            border-color: #00F0FF;
            background-color: #222;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col font-cyber selection:bg-cp_red selection:text-white relative">
    <!-- Grid overlay -->
    <div class="fixed inset-0 z-[-1] opacity-10"
        style="background-image: linear-gradient(#333 1px, transparent 1px), linear-gradient(90deg, #333 1px, transparent 1px); background-size: 30px 30px;">
    </div>

    <header
        class="bg-cp_gray border-b-2 border-cp_red py-4 px-6 relative z-10 flex justify-between items-center shadow-lg shadow-cp_red/20">
        <div>
            <a href="/" class="text-3xl font-bold text-white tracking-widest uppercase flex items-center gap-2">
                <span class="text-cp_red text-4xl leading-none">►</span>Project<span
                    class="text-cp_yellow">_Eagle</span>
            </a>
            <div class="text-cp_cyan text-xs tracking-widest mt-1">SYS.OP.VER.2.0 // DECRYPT.NET</div>
        </div>
        <nav>
            <a href="/"
                class="text-gray-400 hover:text-cp_yellow transition-colors font-bold tracking-wider px-4 py-2 border border-transparent hover:border-cp_yellow border-dashed">HOME_</a>
        </nav>
    </header>

    <main class="flex-grow container mx-auto px-4 py-12 z-10">
        <?php echo $content ?? ''; ?>
    </main>

    <footer class="bg-cp_gray border-t-2 border-cp_cyan mt-auto py-8 z-10 relative">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-500 font-bold tracking-widest text-lg">PROJECT EAGLE &copy; 2026. SECURE SYSTEMS.</p>
            <p
                class="text-cp_red text-sm mt-3 tracking-widest max-w-md mx-auto border border-cp_red p-1 bg-cp_red/10 animate-pulse">
                UNAUTHORIZED ACCESS STRICTLY PROHIBITED.</p>
        </div>
    </footer>
</body>

</html>