(function() {
    const style = document.createElement('style');
    style.innerHTML = `
        .iw-modal { position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; z-index:10000; padding:20px; border-radius:8px; box-shadow:0 10px 40px rgba(0,0,0,0.4); width:320px; font-family:sans-serif; border:1px solid #ddd; }
        .iw-overlay { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; backdrop-filter: blur(2px); }
        .iw-title { margin:0 0 15px 0; font-size:16px; color:#333; font-weight:bold; text-align:center; }
        .iw-group { margin-bottom:12px; }
        .iw-label { display:block; font-size:12px; color:#666; margin-bottom:4px; }
        .iw-select, .iw-input { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; }
        .iw-footer { display:flex; gap:10px; margin-top:20px; }
        .iw-btn { flex:1; padding:10px; cursor:pointer; border:none; border-radius:4px; font-weight:bold; }
        .iw-btn-go { background:#28a745; color:#fff; }
        .iw-btn-cancel { background:#eee; color:#333; }
    `;
    document.head.appendChild(style);

    window.openImageWork = function(pathValue) {
        const overlay = document.createElement('div');
        overlay.className = 'iw-overlay';
        const modal = document.createElement('div');
        modal.className = 'iw-modal';
        
        modal.innerHTML = `
            <div class="iw-title">⚡ ImageWork Optimizer</div>
            <div class="iw-group">
                <span class="iw-label">Format de sortie :</span>
                <select id="iw-format" class="iw-select">
                    <option value="avif">AVIF (Ultra léger - Conseillé)</option>
                    <option value="webp">WebP (Standard moderne)</option>
                </select>
            </div>
            <div class="iw-group">
                <span class="iw-label">Largeur max (pixels) :</span>
                <input type="number" id="iw-width" class="iw-input" value="1600" step="100">
            </div>
            <div class="iw-footer">
                <button class="iw-btn iw-btn-cancel" id="iw-cancel">Annuler</button>
                <button class="iw-btn iw-btn-go" id="iw-save">Compresser</button>
            </div>
        `;

        document.body.appendChild(overlay);
        overlay.appendChild(modal);

        document.getElementById('iw-cancel').onclick = () => overlay.remove();
        
        document.getElementById('iw-save').onclick = function() {
            const format = document.getElementById('iw-format').value;
            const width = document.getElementById('iw-width').value;
            this.innerHTML = "Traitement...";
            this.disabled = true;

            const fd = new FormData();
            fd.append('src', pathValue);
            fd.append('format', format);
            fd.append('width', width);
            
            fetch('core/vendor/filemanager/picturetools/img_processor.php', { method: 'POST', body: fd })
            .then(r => r.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if(data.status === 'success') {
                        alert("Succès !\nFormat: " + data.format.toUpperCase() + "\nGain: " + data.gain);
                    } else {
                        alert("Erreur : " + data.error);
                    }
                } catch(e) {
                    alert("Erreur PHP:\n" + text);
                }
                overlay.remove();
            })
            .catch(err => {
                alert("Erreur réseau: " + err.message);
                overlay.remove();
            });
        };
    };

    function injectBtn(doc) {
        doc.querySelectorAll('figure[data-name]').forEach(fig => {
            const pathValue = fig.getAttribute('data-path') || fig.getAttribute('data-name');
            if (pathValue && pathValue.match(/\.(jpg|jpeg|png)$/i) && !fig.querySelector('.p-tools')) {
                const btn = document.createElement('div');
                btn.className = 'p-tools';
                btn.innerHTML = '⚡';
                btn.style = "position:absolute;top:5px;right:5px;background:#28a745;color:#fff;padding:2px 6px;border-radius:3px;cursor:pointer;z-index:1000;font-weight:bold;font-size:11px;";
                btn.onclick = (e) => { e.preventDefault(); e.stopPropagation(); window.openImageWork(pathValue); };
                fig.appendChild(btn);
            }
        });
    }

    setInterval(() => {
        injectBtn(document);
        document.querySelectorAll('iframe').forEach(iframe => {
            try { injectBtn(iframe.contentDocument || iframe.contentWindow.document); } catch (e) {}
        });
    }, 2000);
})();