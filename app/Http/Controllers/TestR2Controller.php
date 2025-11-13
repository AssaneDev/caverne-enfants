<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestR2Controller extends Controller
{
    public function testConnection()
    {
        try {
            // Vérifier si les variables d'environnement sont configurées
            $config = [
                'R2_ACCOUNT_ID' => env('R2_ACCOUNT_ID'),
                'R2_ACCESS_KEY_ID' => env('R2_ACCESS_KEY_ID'),
                'R2_SECRET_ACCESS_KEY' => env('R2_SECRET_ACCESS_KEY') ? '***configured***' : null,
                'R2_BUCKET' => env('R2_BUCKET'),
                'R2_PUBLIC_URL' => env('R2_PUBLIC_URL'),
                'R2_ENDPOINT' => env('R2_ENDPOINT'),
            ];

            $missingVars = [];
            foreach ($config as $key => $value) {
                if (empty($value)) {
                    $missingVars[] = $key;
                }
            }

            if (!empty($missingVars)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Variables d\'environnement manquantes',
                    'missing_vars' => $missingVars,
                    'config' => $config
                ], 400);
            }

            // Tester la connexion au bucket R2
            $disk = Storage::disk('r2');

            // Créer un fichier de test
            $testFile = 'test-' . time() . '.txt';
            $testContent = 'Test de connexion R2 Cloudflare - ' . now();

            $disk->put($testFile, $testContent);

            // Vérifier que le fichier existe
            $exists = $disk->exists($testFile);

            // Récupérer le contenu
            $content = $disk->get($testFile);

            // Obtenir l'URL si disponible
            $url = null;
            if (env('R2_PUBLIC_URL')) {
                $url = $disk->url($testFile);
            }

            // Supprimer le fichier de test
            $disk->delete($testFile);

            return response()->json([
                'status' => 'success',
                'message' => 'Connexion R2 réussie !',
                'test_results' => [
                    'file_created' => true,
                    'file_exists' => $exists,
                    'content_match' => $content === $testContent,
                    'file_deleted' => !$disk->exists($testFile),
                    'public_url' => $url
                ],
                'config' => $config
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de connexion R2',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function uploadTest(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aucun fichier fourni'
                ], 400);
            }

            $file = $request->file('file');
            $disk = Storage::disk('r2');

            $path = $disk->putFile('uploads', $file);

            $url = null;
            if (env('R2_PUBLIC_URL')) {
                $url = $disk->url($path);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Fichier uploadé avec succès',
                'path' => $path,
                'public_url' => $url,
                'size' => $disk->size($path),
                'mime_type' => $disk->mimeType($path)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'upload',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listFiles()
    {
        try {
            $disk = Storage::disk('r2');
            $files = $disk->allFiles();

            $fileList = [];
            foreach ($files as $file) {
                $fileList[] = [
                    'path' => $file,
                    'size' => $disk->size($file),
                    'last_modified' => $disk->lastModified($file),
                    'mime_type' => $disk->mimeType($file)
                ];
            }

            return response()->json([
                'status' => 'success',
                'total_files' => count($fileList),
                'files' => $fileList
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des fichiers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
