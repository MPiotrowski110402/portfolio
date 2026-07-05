<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Portfolio | PHP Developer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }

        .typing-effect {
            overflow: hidden; white-space: nowrap; border-right: 2px solid #10b981;
            width: 0; animation: typing 2s steps(40, end) forwards, blink 1s step-end infinite;
        }
        @keyframes typing { from { width: 0 } to { width: 100% } }
        @keyframes blink { from, to { border-color: transparent } 50% { border-color: #10b981; } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        .iframe-wrapper::before {
            content: ''; position: absolute; inset: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 38 38" stroke="%2310b981"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="2"><circle stroke-opacity=".2" cx="18" cy="18" r="18"/><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"/></path></g></g></svg>') center no-repeat;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-300 h-screen flex flex-col font-sans antialiased overflow-hidden relative">

    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-emerald-600/20 rounded-full mix-blend-screen filter blur-[100px] opacity-50 animate-blob"></div>
        <div class="absolute top-[20%] right-[-5%] w-96 h-96 bg-blue-600/20 rounded-full mix-blend-screen filter blur-[100px] opacity-50 animate-blob animation-delay-2000"></div>
    </div>

    <nav class="bg-[#0f172a]/80 backdrop-blur-md border-b border-slate-800/80 px-6 py-3 flex-none z-10">
        <div class="max-w-full mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-bold text-slate-900 text-lg shadow-[0_0_15px_rgba(16,185,129,0.3)]">MP</div>
                <div>
                    <span class="font-bold text-white text-sm block tracking-wide">Mateusz P. | Portfolio</span>
                    <span class="text-[10px] text-emerald-400 font-mono flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block mr-1 animate-pulse"></span>System Online</span>
                </div>
            </div>
            <div class="flex items-center space-x-5">
                <div class="hidden sm:flex flex-col text-right font-mono">
                    <span id="live-time" class="text-xs text-slate-300">00:00:00</span>
                    <span class="text-[9px] text-slate-500 uppercase tracking-widest">Local Time</span>
                </div>
                <div class="w-px h-6 bg-slate-700 hidden sm:block"></div>
                <a href="https://github.com" target="_blank" class="text-slate-400 hover:text-white hover:scale-110 transition-all"><i class="fab fa-github text-lg"></i></a>
                <a href="mailto:twoj@email.com" class="bg-emerald-600 hover:bg-emerald-500 text-slate-900 font-semibold px-4 py-1.5 rounded-lg text-xs transition-all">Kontakt</a>
            </div>
        </div>
    </nav>

    <div class="flex-1 flex overflow-hidden w-full z-10">
        <aside class="w-full md:w-[350px] lg:w-[450px] bg-[#0b0f19]/90 backdrop-blur-sm border-r border-slate-800 p-4 overflow-y-auto flex-none flex flex-col space-y-4">
            <div class="pb-2 border-b border-slate-800/80 flex justify-between items-end">
                <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 font-mono">Lista projektów</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-[9px] text-slate-400 bg-slate-800/50 px-2 py-0.5 rounded border border-slate-700/50">Sortuj: Najnowsze</span>
                </div>
            </div>

            @forelse($projects as $project)
                <div onclick="loadProject(this, '{{ $project->project_url }}', '{{ $project->title }}')" 
                     class="project-card group bg-[#1e293b]/80 border border-slate-700/50 hover:border-emerald-500/50 hover:-translate-y-1 p-4 rounded-xl cursor-pointer transition-all duration-300 relative overflow-hidden flex flex-col min-h-[140px]">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex justify-between items-start mb-2 gap-2">
                        <h3 class="font-bold text-white text-sm group-hover:text-emerald-400 transition-colors line-clamp-1 flex-1">{{ $project->title }}</h3>
                        <span class="text-[10px] font-mono text-slate-400 whitespace-nowrap bg-[#0f172a]/80 px-1.5 py-0.5 rounded border border-slate-700/50">
                            {{ $project->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <div class="relative mb-3">
                        <p id="desc-{{ $project->id }}" class="text-slate-400 text-xs line-clamp-2 leading-relaxed transition-all duration-300">
                            {{ $project->description }}
                        </p>
                        <button onclick="toggleDescription(event, 'desc-{{ $project->id }}')" class="text-[9px] text-emerald-500 font-bold uppercase hover:underline mt-1">Czytaj więcej</button>
                    </div>

                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        @foreach(explode(',', $project->technologies) as $tech)
                            @php
                                $t = strtolower(trim($tech));
                                $c = 'bg-[#0f172a] text-slate-300 border-slate-700/60';
                                if(str_contains($t, 'laravel')) $c = 'bg-red-500/10 text-red-400 border-red-500/20';
                                elseif(str_contains($t, 'vue')) $c = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                                elseif(str_contains($t, 'php')) $c = 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20';
                                elseif(str_contains($t, 'tailwind')) $c = 'bg-sky-500/10 text-sky-400 border-sky-500/20';
                                elseif(str_contains($t, 'js')) $c = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
                            @endphp
                            <span class="text-[10px] font-mono border px-2 py-0.5 rounded-full {{ $c }}">{{ trim($tech) }}</span>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-slate-500 text-xs text-center">Brak projektów.</p>
            @endforelse
        </aside>

        <main class="flex-1 bg-gradient-to-br from-[#111827] to-[#0f172a] relative flex flex-col h-full shadow-inner">
            <div class="bg-[#1f2937]/90 backdrop-blur-md border-b border-slate-800 px-4 py-2.5 flex items-center justify-between text-xs text-slate-400 flex-none z-10">
                <div class="flex items-center space-x-3 w-full max-w-2xl">
                    <button onclick="closePreview()" class="hover:text-white transition-colors p-1 bg-slate-800 rounded hover:bg-slate-700">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                    </button>
                    <div class="flex space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-slate-600"></span>
                        <span class="w-3 h-3 rounded-full bg-slate-600"></span>
                        <span class="w-3 h-3 rounded-full bg-slate-600"></span>
                    </div>
                    <div class="bg-[#0b0f19] border border-slate-700/50 rounded-lg px-4 py-1.5 flex items-center space-x-2 text-slate-400 w-full font-mono text-[11px] truncate">
                        <i class="fas fa-lock text-emerald-500/70"></i>
                        <span id="browser-url">oczekiwanie_na_akcje.sh</span>
                    </div>
                </div>
                <div id="preview-actions" class="hidden opacity-0 transition-opacity duration-500">
                    <a id="external-link" href="#" target="_blank" class="hover:text-emerald-400 text-slate-400 px-3 py-1 rounded-md bg-slate-800">
                        Otwórz <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="flex-1 relative w-full h-full overflow-hidden iframe-wrapper">
                <div id="about-section" class="absolute inset-0 p-8 flex flex-col justify-center max-w-3xl mx-auto transition-opacity duration-500">
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-3">Cześć, jestem Mateusz.</h2>
                    <p class="text-emerald-400 font-mono typing-effect text-sm">> Fullstack PHP / Laravel Developer_</p>
                    <p class="text-slate-400 mt-4">Wybierz projekt z listy po lewej stronie, aby załadować podgląd.</p>
                </div>
                <iframe id="project-iframe" class="w-full h-full border-none absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700" src=""></iframe>
            </div>
        </main>
    </div>

    <script>
        function updateClock() { document.getElementById('live-time').innerText = new Date().toLocaleTimeString('pl-PL', { hour12: false }); }
        setInterval(updateClock, 1000); updateClock();

        function toggleDescription(e, id) {
            e.stopPropagation();
            const el = document.getElementById(id);
            el.classList.toggle('line-clamp-2');
            e.target.innerText = el.classList.contains('line-clamp-2') ? 'Czytaj więcej' : 'Zwiń';
        }

        function closePreview() {
            const aboutSection = document.getElementById('about-section');
            aboutSection.style.opacity = '1';
            aboutSection.style.pointerEvents = 'auto'; // Przywracamy klikalność ekranu powitalnego
            
            document.getElementById('project-iframe').style.opacity = '0';
            document.getElementById('project-iframe').style.pointerEvents = 'none';
            document.getElementById('preview-actions').classList.add('hidden', 'opacity-0');
            document.getElementById('browser-url').innerText = "oczekiwanie_na_akcje.sh";
            document.querySelectorAll('.project-card').forEach(c => c.classList.remove('border-emerald-500', 'shadow-[0_0_15px_rgba(16,185,129,0.2)]'));
        }

        function loadProject(el, url, title) {
            const aboutSection = document.getElementById('about-section');
            aboutSection.style.opacity = '0';
            aboutSection.style.pointerEvents = 'none'; // Sprawiamy, że ekran powitalny staje się "duchem" i nie blokuje myszki
            
            const iframe = document.getElementById('project-iframe');
            iframe.src = url;
            iframe.style.opacity = '1';
            iframe.style.pointerEvents = 'auto';
            document.getElementById('browser-url').innerText = url;
            document.getElementById('preview-actions').classList.remove('hidden', 'opacity-0');
            document.querySelectorAll('.project-card').forEach(c => c.classList.remove('border-emerald-500', 'shadow-[0_0_15px_rgba(16,185,129,0.2)]'));
            el.classList.add('border-emerald-500', 'shadow-[0_0_15px_rgba(16,185,129,0.2)]');
        }
    </script>
</body>
</html>