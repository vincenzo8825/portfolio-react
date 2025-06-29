<?php
echo "<h1>🧪 TEST NUOVO BUNDLE</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

echo "<div class='info'>🔗 <a href='/' target='_blank'>Apri Portfolio</a></div>";

echo "<h2>📄 File Index.html</h2>";
$html = file_get_contents('index.html');
if (strpos($html, 'index-DgIyTcTH.js') !== false) {
    echo "<div class='success'>✅ Nuovo bundle DgIyTcTH.js trovato in HTML</div>";
} else {
    echo "<div class='error'>❌ Nuovo bundle NON trovato in HTML</div>";
}

if (strpos($html, 'v=1735316701') !== false) {
    echo "<div class='success'>✅ Cache busting attivo</div>";
} else {
    echo "<div class='error'>❌ Cache busting NON attivo</div>";
}

echo "<h2>📁 File Assets</h2>";
$assets = glob('assets/*');
foreach ($assets as $asset) {
    $size = number_format(filesize($asset)/1024, 1);
    echo "<div class='info'>📄 " . basename($asset) . " - {$size} KB</div>";
}

echo "<h2>🧪 Test JavaScript</h2>";
echo "<script>
// Test per verificare quale bundle viene caricato
console.log('🧪 Test nuovo bundle attivo');

// Controlla quale file JS è caricato
const scripts = document.querySelectorAll('script[src*=\"index-\"]');
scripts.forEach(script => {
    console.log('📄 Script caricato:', script.src);
    if (script.src.includes('DgIyTcTH')) {
        console.log('✅ NUOVO bundle caricato correttamente!');
        document.body.innerHTML += '<div style=\"background:green;color:white;padding:10px;margin:10px;\">✅ NUOVO BUNDLE CARICATO!</div>';
    } else if (script.src.includes('DhKCdsqZ')) {
        console.log('❌ VECCHIO bundle ancora attivo!');
        document.body.innerHTML += '<div style=\"background:red;color:white;padding:10px;margin:10px;\">❌ VECCHIO BUNDLE ANCORA ATTIVO!</div>';
    }
});

// Verifica presenza API configurazione
setTimeout(() => {
    if (typeof window.fetch !== 'undefined') {
        // Test API call
        fetch('/api/v1/test')
            .then(r => r.json())
            .then(data => {
                console.log('✅ API Test successful:', data);
                document.body.innerHTML += '<div style=\"background:blue;color:white;padding:10px;margin:10px;\">✅ API FUNZIONANTE: ' + (data.message || 'OK') + '</div>';
            })
            .catch(e => {
                console.log('❌ API Test failed:', e);
                document.body.innerHTML += '<div style=\"background:orange;color:white;padding:10px;margin:10px;\">⚠️ API Error: ' + e.message + '</div>';
            });
    }
}, 1000);
</script>";

echo "<h2>🔄 Istruzioni</h2>";
echo "<div class='info'>1. Apri il portfolio dal link sopra</div>";
echo "<div class='info'>2. Apri DevTools (F12) → Console</div>";
echo "<div class='info'>3. Verifica che NON ci siano più errori 'Failed to fetch projects'</div>";
echo "<div class='info'>4. Dovresti vedere: '✅ NUOVO bundle caricato correttamente!'</div>";

echo "<br><div class='success'>🎯 Se vedi ancora il vecchio bundle, prova CTRL+SHIFT+R (super hard refresh)</div>";
?> 