<?php
/**
 * Fonctions utilitaires pour le filemanager ZwiiCMS
 * Gestion de la lecture des fichiers JsonDb (compression + cryptage)
 */

/**
 * Fonction universelle pour lire user.json (compressé et/ou crypté)
 * @param string $filePath Chemin vers le fichier JSON
 * @return array|null Données décodées ou null en cas d'erreur
 */
function readUserJsonFile($filePath) {
    $content = file_get_contents($filePath);
    
    if ($content === false) {
        return null;
    }
    
    // Détecter si le contenu est crypté (tableau) ou compressé (chaîne)
    $decoded = json_decode($content, true);
    
    if (is_array($decoded)) {
        // Le contenu est déjà un tableau JSON (non compressé, peut-être crypté)
        $data = $decoded;
    } else {
        // Le contenu est une chaîne, essayer de décompresser
        $isCompressed = (substr($content, 0, 2) === "\x78\x9c" ||  // ZLIB default
                         substr($content, 0, 2) === "\x78\xda" ||  // ZLIB best
                         substr($content, 0, 2) === "\x78\x5e");   // ZLIB fast
        
        if ($isCompressed) {
            $decompressed = gzuncompress($content);
            if ($decompressed !== false) {
                $data = json_decode($decompressed, true);
            } else {
                return null;
            }
        } else {
            // Ni compressé ni JSON valide
            return null;
        }
    }
    
    // Vérifier si les données sont cryptées
    if (isset($data['encrypted']) && $data['encrypted'] === true) {
        // Lire la clé de cryptage depuis config.json
        $configPath = '../../../site/data/config.json';
        $configContent = file_get_contents($configPath);
        
        if ($configContent === false) {
            return null;
        }
        
        // Détecter si config est aussi compressé
        $configDecoded = json_decode($configContent, true);
        
        if (!is_array($configDecoded)) {
            // Config compressé
            $isConfigCompressed = (substr($configContent, 0, 2) === "\x78\x9c" || 
                                   substr($configContent, 0, 2) === "\x78\xda" || 
                                   substr($configContent, 0, 2) === "\x78\x5e");
            
            if ($isConfigCompressed) {
                $configDecompressed = gzuncompress($configContent);
                if ($configDecompressed !== false) {
                    $configData = json_decode($configDecompressed, true);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            $configData = $configDecoded;
        }
        
        // Récupérer la clé de cryptage et la configuration de la base user
        $encryptionKey = $configData['config']['database']['user']['encryptionKey'] ?? null;
        $userEncryption = $configData['config']['database']['user']['encryption'] ?? false;
        
        // Vérifier si la base user doit être décryptée
        // Forcer le décryptage si les données sont marquées comme cryptées, même si la config dit non
        if ($encryptionKey && isset($data['data'])) {
            // Décrypter les données (méthode JsonDb)
            $encryptedData = base64_decode($data['data']);
            $iv = substr($encryptedData, 0, 16);          // IV = 16 premiers octets
            $encrypted = substr($encryptedData, 16);       // Données = reste
            
            $decrypted = openssl_decrypt(
                $encrypted,
                'AES-256-CBC',
                $encryptionKey,
                OPENSSL_RAW_DATA,
                $iv
            );
            
            if ($decrypted !== false) {
                $data = json_decode($decrypted, true);
            } else {
                return null;
            }
        }
        // Si les données sont marquées comme cryptées mais que la config dit non,
        // on garde les données telles quelles (peut-être une incohérence)
    }
    
    return $data;
}
