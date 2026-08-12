<?php
if (!defined('APP_VERSION')) {
    define('APP_VERSION', '0.2.0'); // Cambia a una versione inferiore a quella su GitHub (es. 0.2.0) per vedere il banner
}

function checkLatestVersion() {
    $cacheFile = sys_get_temp_dir() . '/aegis_ca_latest_version.txt';
    $cacheTime = 7200; // 2 ore

    // Per testare SUBITO il banner ed evitare la cache vecchia, scommenta la riga sotto:
    // @unlink($cacheFile);

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
        return file_get_contents($cacheFile);
    }

    $url = "https://api.github.com/repos/P1c1s/AegisCA/releases/latest";
    $options = [
        "http" => [
            "header" => "User-Agent: AegisCA-App\r\n",
            "timeout" => 3
        ]
    ];
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['tag_name'])) {
            $latestVersion = ltrim($data['tag_name'], 'v');
            file_put_contents($cacheFile, $latestVersion);
            return $latestVersion;
        }
    }

    return APP_VERSION; // Corretto il refuso (rimossa la "s")
}

$latestVersion = checkLatestVersion();
$hasUpdate = version_compare($latestVersion, APP_VERSION, '>');
?>

<div class="footer">
    <div class="footer-meta">
        <p>
            &copy; <?php echo date('Y'); ?> 
            <strong><a href="https://github.com/P1c1s/AegisCA" target="_blank" rel="noopener">Aegis CA</a></strong> 
            &bull; Sviluppato da <a href="https://github.com/P1c1s" target="_blank" rel="noopener">P1c1s</a>
        </p>
        
        <p class="version">
            <a href="https://github.com/P1c1s/AegisCA/releases/tag/v<?php echo APP_VERSION; ?>" target="_blank" rel="noopener">v<?php echo APP_VERSION; ?></a> &bull; Licenza MIT            <?php if ($hasUpdate): ?>
                &bull; <a href="https://github.com/P1c1s/AegisCA/releases/latest" target="_blank" rel="noopener" class="update-badge">
                    <i class="fa-solid fa-circle-arrow-up"></i> Nuova versione disponibile (v<?php echo htmlspecialchars($latestVersion); ?>)
                </a>
            <?php endif; ?>
        </p>
    </div>
</div>