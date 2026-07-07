<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Portfolio | PHP Developer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
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

        /* Spinner ładowania pokazywany tylko na chwilę przy przełączaniu projektu/widoku */
        #loading-spinner {
            position: absolute; inset: 0; z-index: 5;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity 0.2s ease;
        }
        #loading-spinner.active { opacity: 1; }

        /* --- Boot loader --- */
        #boot-loader {
            transition: opacity 0.4s ease;
        }
        .boot-line { opacity: 0; animation: bootLineIn 0.25s ease forwards; }
        @keyframes bootLineIn { from { opacity: 0; transform: translateX(-6px); } to { opacity: 1; transform: translateX(0); } }

        /* --- Wejście elementów strony --- */
        .entrance { opacity: 0; }
        .entrance.play { animation: fadeUp 0.6s ease forwards; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }

        .card-in { opacity: 0; animation: fadeUp 0.5s ease forwards; }

        /* --- Panel kontaktowy --- */
        #contact-panel {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.3s ease;
        }
        #contact-panel.open { max-height: 260px; opacity: 1; }

        /* --- Przejście podglądu projektu --- */
        #project-iframe, #code-view {
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        #file-tree .fa-chevron-right { transition: transform 0.15s ease; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-300 h-screen flex flex-col font-sans antialiased overflow-hidden relative">

    <!-- Boot loader — krótka animacja "startu systemu" -->
    <div id="boot-loader" class="fixed inset-0 bg-[#0f172a] z-50 flex items-center justify-center">
        <div class="font-mono text-[11px] sm:text-xs text-emerald-400 space-y-1.5 w-72" id="boot-lines"></div>
    </div>

    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-emerald-600/20 rounded-full mix-blend-screen filter blur-[100px] opacity-50 animate-blob"></div>
        <div class="absolute top-[20%] right-[-5%] w-96 h-96 bg-blue-600/20 rounded-full mix-blend-screen filter blur-[100px] opacity-50 animate-blob animation-delay-2000"></div>
    </div>

    <nav class="entrance bg-[#0f172a]/80 backdrop-blur-md border-b border-slate-800/80 px-6 py-3 flex-none z-20 relative">
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
                <button onclick="toggleContact()" id="contact-btn" class="bg-emerald-600 hover:bg-emerald-500 text-slate-900 font-semibold px-4 py-1.5 rounded-lg text-xs transition-all flex items-center space-x-1.5">
                    <span>Kontakt</span>
                    <i id="contact-chevron" class="fas fa-chevron-down text-[9px] transition-transform"></i>
                </button>
            </div>
        </div>

        <!-- Rozsuwany panel kontaktowy -->
        <div id="contact-panel" class="absolute right-6 top-full w-72 bg-[#1e293b] border border-slate-700/60 rounded-xl shadow-2xl mt-2 z-30">
            <div class="p-4 space-y-3">
                <div class="text-[10px] uppercase tracking-widest text-slate-500 font-mono mb-1">Dane kontaktowe</div>

                <a href="mailto:twoj@email.com" class="flex items-center space-x-2.5 text-xs text-slate-300 hover:text-emerald-400 transition-colors">
                    <i class="fas fa-envelope w-4 text-emerald-500/80"></i>
                    <span>twoj@email.com</span>
                </a>
                <a href="https://github.com" target="_blank" class="flex items-center space-x-2.5 text-xs text-slate-300 hover:text-emerald-400 transition-colors">
                    <i class="fab fa-github w-4 text-emerald-500/80"></i>
                    <span>github.com/twoj-nick</span>
                </a>
                <a href="https://linkedin.com" target="_blank" class="flex items-center space-x-2.5 text-xs text-slate-300 hover:text-emerald-400 transition-colors">
                    <i class="fab fa-linkedin w-4 text-emerald-500/80"></i>
                    <span>linkedin.com/in/twoj-profil</span>
                </a>
                <div class="flex items-center space-x-2.5 text-xs text-slate-300">
                    <i class="fas fa-phone w-4 text-emerald-500/80"></i>
                    <span>+48 000 000 000</span>
                </div>

                <div class="pt-2 border-t border-slate-700/60">
                    <a href="mailto:twoj@email.com" class="block text-center text-[11px] font-semibold bg-emerald-600 hover:bg-emerald-500 text-slate-900 py-1.5 rounded-lg transition-all">
                        Napisz e-mail
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-1 flex overflow-hidden w-full z-10">
        <aside class="entrance w-full md:w-[350px] lg:w-[450px] bg-[#0b0f19]/90 backdrop-blur-sm border-r border-slate-800 p-4 overflow-y-auto flex-none flex flex-col space-y-4">
            <div class="pb-2 border-b border-slate-800/80 flex justify-between items-end">
                <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 font-mono">Lista projektów</h2>
                <button onclick="cycleSort()" class="text-[9px] text-slate-400 bg-slate-800/50 hover:bg-slate-800 hover:text-emerald-400 px-2 py-0.5 rounded border border-slate-700/50 transition-colors flex items-center space-x-1">
                    <i class="fas fa-arrow-down-wide-short text-[8px]"></i>
                    <span>Sortuj: <span id="sort-label">Najnowsze</span></span>
                </button>
            </div>

            <div id="project-list" class="flex flex-col space-y-4">
                @forelse($projects as $project)
                    <div onclick="loadProject(this, '{{ $project->project_url }}', '{{ addslashes($project->title) }}', {{ $project->id }}, {{ $project->github_url ? "'" . addslashes($project->github_url) . "'" : 'null' }})"
                         data-created="{{ $project->created_at->timestamp }}"
                         data-title="{{ addslashes($project->title) }}"
                         style="animation-delay: {{ $loop->index * 0.06 }}s"
                         class="project-card card-in group bg-[#1e293b]/80 border border-slate-700/50 hover:border-emerald-500/50 hover:-translate-y-1 p-4 rounded-xl cursor-pointer transition-all duration-300 relative overflow-hidden flex flex-col min-h-[140px]">

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

                        <div class="flex flex-wrap items-center gap-1.5 mt-auto">
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

                            @if($project->github_url)
                                <span class="text-[9px] font-mono border px-2 py-0.5 rounded-full bg-slate-700/30 text-slate-400 border-slate-600/40 flex items-center gap-1 ml-auto">
                                    <i class="fas fa-code text-[8px]"></i> kod dostępny
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 text-xs text-center">Brak projektów.</p>
                @endforelse
            </div>
        </aside>

        <main class="entrance flex-1 bg-gradient-to-br from-[#111827] to-[#0f172a] relative flex flex-col h-full shadow-inner">
            <div class="bg-[#1f2937]/90 backdrop-blur-md border-b border-slate-800 px-4 py-2.5 flex items-center justify-between text-xs text-slate-400 flex-none z-10">
                <div class="flex items-center space-x-3 w-full max-w-2xl">
                    <button onclick="closePreview()" class="hover:text-white transition-colors p-1 bg-slate-800 rounded hover:bg-slate-700">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                    </button>
                    <div class="flex space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-[#ff5f56]"></span>
                        <span class="w-3 h-3 rounded-full bg-[#ffbd2e]"></span>
                        <span class="w-3 h-3 rounded-full bg-[#27c93f]"></span>
                    </div>
                    <div class="bg-[#0b0f19] border border-slate-700/50 rounded-lg px-4 py-1.5 flex items-center space-x-2 text-slate-400 w-full font-mono text-[11px] truncate">
                        <i class="fas fa-lock text-emerald-500/70"></i>
                        <span id="browser-url">oczekiwanie_na_akcje.sh</span>
                    </div>
                </div>
                <div id="preview-actions" class="hidden opacity-0 transition-opacity duration-500 items-center space-x-2 flex-none">
                    <button id="toggle-code-btn" onclick="toggleView()" disabled
                            class="opacity-40 cursor-not-allowed hover:text-emerald-400 text-slate-400 px-3 py-1 rounded-md bg-slate-800 flex items-center space-x-1.5 transition-colors whitespace-nowrap">
                        <i id="toggle-code-icon" class="fas fa-code text-[10px]"></i>
                        <span id="toggle-code-label">Kod</span>
                    </button>
                </div>
            </div>

            <div class="flex-1 relative w-full h-full overflow-hidden">
                <div id="loading-spinner">
                    <svg width="40" height="40" viewBox="0 0 38 38" stroke="#10b981" xmlns="http://www.w3.org/2000/svg">
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)" stroke-width="2">
                                <circle stroke-opacity=".2" cx="18" cy="18" r="18"/>
                                <path d="M36 18c0-9.94-8.06-18-18-18">
                                    <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"/>
                                </path>
                            </g>
                        </g>
                    </svg>
                </div>
                <div id="about-section" class="absolute inset-0 p-8 flex flex-col justify-center max-w-3xl mx-auto transition-opacity duration-500 overflow-y-auto">
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-3">Cześć, jestem Mateusz.</h2>
                    <p class="text-emerald-400 font-mono typing-effect text-sm">> Fullstack PHP / Laravel Developer_</p>

                    <p class="text-slate-400 mt-5 leading-relaxed text-sm max-w-xl">
                        Na co dzień piszę backend w PHP i Laravelu — API, integracje, panele administracyjne.
                        Środowisko developerskie i wdrożenia stawiam na Dockerze, a po godzinach ogarniam też appki mobilne.
                        Lubię porządny kod bardziej niż ładne demo — ale to akurat też potrafię zrobić.
                    </p>

                    <div class="flex flex-wrap gap-2 mt-5">
                        <span class="text-[11px] font-mono border px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border-indigo-500/20">PHP</span>
                        <span class="text-[11px] font-mono border px-2.5 py-1 rounded-full bg-red-500/10 text-red-400 border-red-500/20">Laravel</span>
                        <span class="text-[11px] font-mono border px-2.5 py-1 rounded-full bg-sky-500/10 text-sky-400 border-sky-500/20">Docker</span>
                        <span class="text-[11px] font-mono border px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border-emerald-500/20">Mobile</span>
                        <span class="text-[11px] font-mono border px-2.5 py-1 rounded-full bg-yellow-500/10 text-yellow-400 border-yellow-500/20">JavaScript</span>
                    </div>

                    <p class="text-slate-500 mt-6 text-xs font-mono">> Wybierz projekt z listy po lewej, aby załadować podgląd_</p>
                </div>

                <iframe id="project-iframe" class="w-full h-full border-none absolute inset-0 opacity-0 scale-[0.98] pointer-events-none" src=""></iframe>

                <div id="code-view" class="absolute inset-0 hidden z-20 bg-[#0d1117] opacity-0 scale-[0.98]">
                    <div class="flex h-full">
                        <aside class="w-64 flex-none border-r border-slate-800 overflow-y-auto p-3">
                            <div class="text-[10px] uppercase tracking-widest text-slate-500 font-mono mb-2 flex items-center space-x-1.5">
                                <i class="fas fa-folder-tree"></i>
                                <span>Struktura repozytorium</span>
                            </div>
                            <div id="file-tree" class="font-mono"></div>
                        </aside>
                        <div class="flex-1 flex flex-col overflow-hidden">
                            <div class="flex-none px-4 py-2 border-b border-slate-800 text-[11px] font-mono text-slate-400 flex items-center space-x-2">
                                <i class="fas fa-file-code text-emerald-500/70"></i>
                                <span id="code-file-name">Wybierz plik z drzewa po lewej</span>
                            </div>
                            <div class="flex-1 overflow-auto p-4">
                                <pre class="!bg-transparent !p-0 m-0"><code id="code-content" class="hljs text-[12px] leading-relaxed"></code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        /* ============ Zegar ============ */
        function updateClock() { document.getElementById('live-time').innerText = new Date().toLocaleTimeString('pl-PL', { hour12: false }); }
        setInterval(updateClock, 1000); updateClock();

        /* ============ Boot loader + wejście strony ============ */
        const bootLines = [
            '[ ok ] Inicjalizacja środowiska...',
            '[ ok ] Montowanie modułów Laravel...',
            '[ ok ] Łączenie z Docker daemon...',
            '[ ok ] Ładowanie projektów...',
            '[ ok ] System gotowy.',
        ];

        function runBootSequence() {
            const container = document.getElementById('boot-lines');
            bootLines.forEach((line, i) => {
                setTimeout(() => {
                    const div = document.createElement('div');
                    div.className = 'boot-line';
                    div.innerText = line;
                    container.appendChild(div);
                }, i * 180);
            });

            const totalTime = bootLines.length * 180 + 400;
            setTimeout(() => {
                const loader = document.getElementById('boot-loader');
                loader.style.opacity = '0';
                setTimeout(() => { loader.style.display = 'none'; }, 400);

                document.querySelectorAll('.entrance').forEach((el, i) => {
                    setTimeout(() => el.classList.add('play'), i * 120);
                });
            }, totalTime);
        }
        document.addEventListener('DOMContentLoaded', runBootSequence);

        /* ============ Opis projektu (rozwiń/zwiń) ============ */
        function toggleDescription(e, id) {
            e.stopPropagation();
            const el = document.getElementById(id);
            el.classList.toggle('line-clamp-2');
            e.target.innerText = el.classList.contains('line-clamp-2') ? 'Czytaj więcej' : 'Zwiń';
        }

        /* ============ Panel kontaktowy ============ */
        function toggleContact() {
            document.getElementById('contact-panel').classList.toggle('open');
            document.getElementById('contact-chevron').classList.toggle('rotate-180');
        }

        /* ============ Sortowanie listy projektów ============ */
        let sortMode = 'newest'; // newest -> oldest -> az -> newest
        const sortLabels = { newest: 'Najnowsze', oldest: 'Najstarsze', az: 'Nazwa A-Z' };

        function cycleSort() {
            const modes = ['newest', 'oldest', 'az'];
            sortMode = modes[(modes.indexOf(sortMode) + 1) % modes.length];
            document.getElementById('sort-label').innerText = sortLabels[sortMode];
            applySort();
        }

        function applySort() {
            const container = document.getElementById('project-list');
            const cards = Array.from(container.querySelectorAll('.project-card'));

            cards.sort((a, b) => {
                if (sortMode === 'az') {
                    return a.dataset.title.localeCompare(b.dataset.title, 'pl');
                }
                const diff = Number(b.dataset.created) - Number(a.dataset.created);
                return sortMode === 'newest' ? diff : -diff;
            });

            cards.forEach((card, i) => {
                card.style.animation = 'none';
                void card.offsetWidth; // wymuszenie reflow, żeby animacja odpaliła się ponownie
                card.style.animationDelay = `${i * 0.05}s`;
                card.style.animation = '';
                card.classList.remove('card-in');
                requestAnimationFrame(() => card.classList.add('card-in'));
                container.appendChild(card);
            });
        }

        /* ============ Stan aktualnie otwartego projektu i widoku ============ */
        let currentProject = null; // { id, url, title, repoUrl }
        let viewMode = 'preview';  // 'preview' | 'code'
        const treeCache = {};      // klucz: "<projectId>::<path>" -> items

        function closePreview() {
            currentProject = null;
            viewMode = 'preview';

            const aboutSection = document.getElementById('about-section');
            aboutSection.style.opacity = '1';
            aboutSection.style.pointerEvents = 'auto';

            const iframe = document.getElementById('project-iframe');
            iframe.style.opacity = '0';
            iframe.style.transform = 'scale(0.98)';
            iframe.style.pointerEvents = 'none';
            iframe.src = '';

            document.getElementById('code-view').classList.add('hidden');
            document.getElementById('loading-spinner').classList.remove('active');
            document.getElementById('preview-actions').classList.add('hidden', 'opacity-0');
            document.getElementById('preview-actions').classList.remove('flex');
            document.getElementById('browser-url').innerText = "oczekiwanie_na_akcje.sh";
            document.querySelectorAll('.project-card').forEach(c => c.classList.remove('border-emerald-500', 'shadow-[0_0_15px_rgba(16,185,129,0.2)]'));

            updateToggleButton();
        }

        function loadProject(el, url, title, id, repoUrl) {
            currentProject = { id, url, title, repoUrl: repoUrl || null };
            viewMode = 'preview';

            const aboutSection = document.getElementById('about-section');
            aboutSection.style.opacity = '0';
            aboutSection.style.pointerEvents = 'none';

            // Krótka faza "ładowania" w pasku adresu, zanim pokażemy realny URL
            const urlEl = document.getElementById('browser-url');
            urlEl.innerText = 'Łączenie z ' + url + ' ...';

            const spinner = document.getElementById('loading-spinner');
            spinner.classList.add('active');

            const iframe = document.getElementById('project-iframe');
            iframe.src = url;

            const actions = document.getElementById('preview-actions');
            actions.classList.remove('hidden', 'opacity-0');
            actions.classList.add('flex');

            document.querySelectorAll('.project-card').forEach(c => c.classList.remove('border-emerald-500', 'shadow-[0_0_15px_rgba(16,185,129,0.2)]'));
            el.classList.add('border-emerald-500', 'shadow-[0_0_15px_rgba(16,185,129,0.2)]');

            updateToggleButton();

            setTimeout(() => {
                iframe.style.opacity = '1';
                iframe.style.transform = 'scale(1)';
                iframe.style.pointerEvents = 'auto';
                urlEl.innerText = url;
                spinner.classList.remove('active');
            }, 350);
        }

        function updateToggleButton() {
            const btn = document.getElementById('toggle-code-btn');
            const hasRepo = currentProject && currentProject.repoUrl;

            btn.disabled = !hasRepo;
            btn.classList.toggle('opacity-40', !hasRepo);
            btn.classList.toggle('cursor-not-allowed', !hasRepo);
            btn.title = hasRepo ? 'Przełącz widok kodu' : 'Brak repozytorium dla tego projektu';
        }

        function toggleView() {
            if (!currentProject || !currentProject.repoUrl) return;
            viewMode = (viewMode === 'preview') ? 'code' : 'preview';
            viewMode === 'code' ? showCode() : showPreview();
        }

        function showPreview() {
            const iframe = document.getElementById('project-iframe');
            const codeView = document.getElementById('code-view');

            codeView.style.opacity = '0';
            codeView.style.transform = 'scale(0.98)';
            setTimeout(() => codeView.classList.add('hidden'), 350);

            iframe.style.pointerEvents = 'auto';
            requestAnimationFrame(() => {
                iframe.style.opacity = '1';
                iframe.style.transform = 'scale(1)';
            });

            document.getElementById('browser-url').innerText = currentProject.url;
            document.getElementById('toggle-code-icon').className = 'fas fa-code text-[10px]';
            document.getElementById('toggle-code-label').innerText = 'Kod';
        }

        function showCode() {
            const iframe = document.getElementById('project-iframe');
            const codeView = document.getElementById('code-view');

            iframe.style.opacity = '0';
            iframe.style.transform = 'scale(0.98)';
            iframe.style.pointerEvents = 'none';

            codeView.classList.remove('hidden');
            requestAnimationFrame(() => {
                codeView.style.opacity = '1';
                codeView.style.transform = 'scale(1)';
            });

            document.getElementById('browser-url').innerText = currentProject.repoUrl;
            document.getElementById('toggle-code-icon').className = 'fas fa-eye text-[10px]';
            document.getElementById('toggle-code-label').innerText = 'Podgląd';

            document.getElementById('code-file-name').innerText = 'Wybierz plik z drzewa po lewej';
            document.getElementById('code-content').innerText = '';

            loadRootTree();
        }

        async function loadRootTree() {
            const container = document.getElementById('file-tree');
            container.innerHTML = '<div class="text-slate-500 text-[11px] p-2">Ładowanie...</div>';

            try {
                const items = await fetchTree('');
                container.innerHTML = '';
                container.appendChild(renderTreeLevel(items, 0));
            } catch (err) {
                container.innerHTML = `<div class="text-red-400 text-[11px] p-2">${err.message}</div>`;
            }
        }

        async function fetchTree(path) {
            const cacheKey = currentProject.id + '::' + path;
            if (treeCache[cacheKey]) return treeCache[cacheKey];

            const res = await fetch(`/projects/${currentProject.id}/code?path=${encodeURIComponent(path)}`);
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Błąd pobierania struktury repozytorium.');

            treeCache[cacheKey] = data.items;
            return data.items;
        }

        function renderTreeLevel(items, depth) {
            const ul = document.createElement('ul');
            ul.className = depth === 0 ? '' : 'ml-3 border-l border-slate-800 pl-2 hidden';

            items.forEach(item => {
                const li = document.createElement('li');
                li.className = 'py-0.5';

                const row = document.createElement('div');
                row.className = 'flex items-center space-x-1.5 cursor-pointer text-slate-300 rounded px-1 py-0.5 hover:bg-slate-800/50 hover:text-emerald-400';

                if (item.type === 'dir') {
                    row.innerHTML = `<i class="fas fa-chevron-right text-[8px] w-2"></i><i class="fas fa-folder text-yellow-500/80 text-[11px]"></i><span class="text-[11px] truncate">${item.name}</span>`;
                } else {
                    row.innerHTML = `<i class="fas fa-chevron-right text-[8px] w-2 opacity-0"></i><i class="fas fa-file-code text-slate-500 text-[11px]"></i><span class="text-[11px] truncate">${item.name}</span>`;
                }

                li.appendChild(row);
                ul.appendChild(li);

                if (item.type === 'dir') {
                    let childUl = null;
                    let loaded = false;

                    row.addEventListener('click', async () => {
                        const chevron = row.querySelector('.fa-chevron-right');

                        if (!loaded) {
                            chevron.classList.add('fa-spin');
                            try {
                                const children = await fetchTree(item.path);
                                childUl = renderTreeLevel(children, depth + 1);
                                li.appendChild(childUl);
                                loaded = true;
                            } catch (err) {
                                chevron.classList.remove('fa-spin');
                                return;
                            }
                            chevron.classList.remove('fa-spin');
                        }

                        const isHidden = childUl.classList.toggle('hidden');
                        chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(90deg)';
                    });
                } else {
                    row.addEventListener('click', () => loadFile(item.path, row));
                }
            });

            return ul;
        }

        async function loadFile(path, rowEl) {
            document.querySelectorAll('#file-tree .bg-emerald-500\\/10').forEach(r => {
                r.classList.remove('bg-emerald-500/10', 'text-emerald-400');
            });
            rowEl.classList.add('bg-emerald-500/10', 'text-emerald-400');

            const codeEl = document.getElementById('code-content');
            document.getElementById('code-file-name').innerText = path;
            codeEl.className = 'hljs text-[12px] leading-relaxed';
            codeEl.innerText = 'Ładowanie...';

            try {
                const res = await fetch(`/projects/${currentProject.id}/code/file?path=${encodeURIComponent(path)}`);
                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Błąd pobierania pliku.');

                const ext = (path.split('.').pop() || '').toLowerCase();
                const langMap = {
                    php: 'php', js: 'javascript', ts: 'typescript', jsx: 'javascript', tsx: 'typescript',
                    vue: 'html', blade: 'html', html: 'html', css: 'css', scss: 'scss',
                    json: 'json', md: 'markdown', yml: 'yaml', yaml: 'yaml', sh: 'bash',
                    sql: 'sql', xml: 'xml', env: 'bash',
                };
                const lang = langMap[ext] || 'plaintext';

                codeEl.className = `hljs language-${lang} text-[12px] leading-relaxed`;
                codeEl.innerText = data.content;
                hljs.highlightElement(codeEl);
            } catch (err) {
                codeEl.innerText = 'Błąd: ' + err.message;
            }
        }
    </script>
</body>
</html>