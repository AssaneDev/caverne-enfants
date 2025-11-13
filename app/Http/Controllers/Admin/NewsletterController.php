<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = Newsletter::orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Newsletter::count(),
            'active' => Newsletter::where('is_active', true)->count(),
            'inactive' => Newsletter::where('is_active', false)->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return back()->with('success', 'Abonné supprimé avec succès.');
    }

    public function toggleStatus(Newsletter $newsletter)
    {
        $newsletter->update([
            'is_active' => !$newsletter->is_active
        ]);

        $status = $newsletter->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Abonné {$status} avec succès.");
    }

    public function export()
    {
        $subscribers = Newsletter::where('is_active', true)->get();

        $filename = 'newsletter_subscribers_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Date d\'inscription', 'Statut']);

            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->created_at->format('d/m/Y H:i'),
                    $subscriber->is_active ? 'Actif' : 'Inactif'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
