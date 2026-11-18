<?php
declare(strict_types=1);

namespace App\Core;

/** La requête HTTP entrante, lue une seule fois. */
final class Request
{
    /**
     * @param array<string, mixed> $query  $_GET
     * @param array<string, mixed> $post   $_POST
     * @param array<string, mixed> $server $_SERVER
     */
    public function __construct(
        private readonly string $methode,
        private readonly string $chemin,
        private readonly string $base,
        private readonly array $query = [],
        private readonly array $post = [],
        private readonly array $server = [],
    ) {
    }

    /** @param array<string, mixed>|null $server */
    public static function fromGlobals(?array $server = null, ?array $get = null, ?array $post = null): self
    {
        $server ??= $_SERVER;
        $uri = (string) parse_url((string) ($server['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

        // Dossier de public/ dans l'URL : '' si le site est à la racine, '/mediatheque/public' sous XAMPP
        $script = (string) ($server['SCRIPT_NAME'] ?? '/index.php');
        $base = rtrim(str_replace('\\', '/', dirname($script)), '/');

        $chemin = $uri;
        if ($base !== '' && str_starts_with($chemin, $base)) {
            $chemin = substr($chemin, strlen($base));
        }
        if (str_starts_with($chemin, '/index.php')) {
            $chemin = substr($chemin, strlen('/index.php'));
        }
        $chemin = '/' . trim($chemin, '/');

        return new self(strtoupper((string) ($server['REQUEST_METHOD'] ?? 'GET')), $chemin, $base, $get ?? $_GET, $post ?? $_POST, $server);
    }

    public function methode(): string
    {
        return $this->methode;
    }

    public function chemin(): string
    {
        return $this->chemin;
    }

    public function base(): string
    {
        return $this->base;
    }

    public function estPost(): bool
    {
        return $this->methode === 'POST';
    }

    public function query(string $cle, mixed $defaut = null): mixed
    {
        return $this->query[$cle] ?? $defaut;
    }

    public function post(string $cle, mixed $defaut = null): mixed
    {
        return $this->post[$cle] ?? $defaut;
    }

    /** @return array<string, mixed> */
    public function toutPost(): array
    {
        return $this->post;
    }
}
