<?php

use Illuminate\Support\Facades\File;

/*test('globals')
    ->expect(['dd', 'dump', 'ray', 'ds'])
    ->not->toBeUsedIn(base_path());*/

test('no debug functions are used', function () {
    $proibidas = ['dd', 'dump', 'ray', 'ds'];
    $pastas    = [
        app_path(),
        base_path('routes'),
        resource_path('views'),
    ];

    foreach ($pastas as $pasta) {
        $arquivos = File::allFiles($pasta);

        foreach ($arquivos as $arquivo) {
            if (str_contains($arquivo->getRealPath(), 'tests/')) {
                continue;
            }

            $conteudo = file_get_contents($arquivo->getRealPath());

            // Remove comentários de linha (//) e de bloco (/* */)
            $conteudo = preg_replace([
                '/\/\/[^\n]*/',             // comentários de linha //
                '/\/\*[\s\S]*?\*\//',       // comentários de bloco /* */
            ], '', $conteudo);

            foreach ($proibidas as $funcao) {
                // verifica chamadas exatas, como: dd( ou dd (
                if (preg_match("/\b{$funcao}\s*\(/", $conteudo)) {
                    $this->fail("⚠️ Função proibida `{$funcao}()` encontrada no arquivo: {$arquivo->getRealPath()}");
                }
            }
        }
    }

    expect(true)->toBeTrue();
});
