<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProjectCodeController extends Controller
{
    /**
     * Zwraca listę plików/folderów dla danej ścieżki w repozytorium.
     */
    public function tree(Project $project, Request $request): JsonResponse
    {
        [$owner, $repo] = $this->parseRepo($project->github_url);

        if (!$owner) {
            return response()->json(['error' => 'Ten projekt nie ma podpiętego repozytorium GitHub.'], 422);
        }

        $path = trim((string) $request->query('path', ''), '/');
        $cacheKey = "gh_tree_{$owner}_{$repo}_{$path}";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($owner, $repo, $path) {
            $url = "https://api.github.com/repos/{$owner}/{$repo}/contents/{$path}";
            $response = $this->githubRequest($url);

            return $response->successful() ? $response->json() : null;
        });

        if (!$data || !is_array($data)) {
            return response()->json(['error' => 'Nie udało się pobrać zawartości repozytorium. Sprawdź czy link jest poprawny i repo jest publiczne.'], 502);
        }

        $items = collect($data)
            ->map(fn ($item) => [
                'name' => $item['name'],
                'path' => $item['path'],
                'type' => $item['type'], // "file" albo "dir"
                'size' => $item['size'] ?? null,
            ])
            ->sortBy(fn ($item) => ($item['type'] === 'dir' ? '0_' : '1_') . strtolower($item['name']))
            ->values();

        return response()->json(['items' => $items, 'path' => $path]);
    }

    /**
     * Zwraca zawartość pojedynczego pliku.
     */
    public function file(Project $project, Request $request): JsonResponse
    {
        [$owner, $repo] = $this->parseRepo($project->github_url);

        if (!$owner) {
            return response()->json(['error' => 'Ten projekt nie ma podpiętego repozytorium GitHub.'], 422);
        }

        $path = trim((string) $request->query('path', ''), '/');

        if ($path === '') {
            return response()->json(['error' => 'Brak ścieżki pliku.'], 422);
        }

        $cacheKey = "gh_file_{$owner}_{$repo}_{$path}";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($owner, $repo, $path) {
            $url = "https://api.github.com/repos/{$owner}/{$repo}/contents/{$path}";
            $response = $this->githubRequest($url);

            return $response->successful() ? $response->json() : null;
        });

        if (!$data || ($data['type'] ?? null) !== 'file') {
            return response()->json(['error' => 'Nie udało się pobrać pliku.'], 502);
        }

        $content = base64_decode($data['content'] ?? '');

        // Zabezpieczenie przed zrzucaniem ogromnych plików do przeglądarki
        if (strlen($content) > 500_000) {
            return response()->json(['error' => 'Plik jest zbyt duży do podglądu w przeglądarce.'], 413);
        }

        return response()->json([
            'name' => $data['name'],
            'path' => $data['path'],
            'content' => $content,
        ]);
    }

    /**
     * Wyciąga owner/repo z linku GitHub, np:
     * https://github.com/owner/repo
     * https://github.com/owner/repo.git
     * https://github.com/owner/repo/
     */
    private function parseRepo(?string $repoUrl): array
    {
        if (!$repoUrl) {
            return [null, null];
        }

        if (preg_match('#github\.com/([^/]+)/([^/]+?)(\.git)?/?(?:$|\?)#', $repoUrl, $matches)) {
            return [$matches[1], $matches[2]];
        }

        return [null, null];
    }

    private function githubRequest(string $url)
    {
        $request = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
        ]);

        if ($token = config('services.github.token')) {
            $request = $request->withToken($token);
        }

        return $request->get($url);
    }
}