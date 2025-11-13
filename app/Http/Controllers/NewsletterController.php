<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterSubscribed;
use App\Models\Newsletter;
use App\Rules\TurnstileValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletters,email'],
            'cf-turnstile-response' => ['required', new TurnstileValid()],
        ], [
            'email.unique' => 'Cet email est déjà inscrit à notre newsletter.',
            'cf-turnstile-response.required' => 'Veuillez compléter la vérification de sécurité.',
        ]);

        $newsletter = Newsletter::create([
            'email' => $request->email,
        ]);

        // Envoyer l'email de confirmation
        Mail::to($newsletter->email)->send(new NewsletterSubscribed($newsletter));

        return back()->with('success', 'Merci ! Vous êtes maintenant inscrit à notre newsletter.');
    }

    public function unsubscribe(Request $request)
    {
        $newsletter = Newsletter::where('email', $request->email)->first();

        if ($newsletter) {
            $newsletter->update(['is_active' => false]);
            return back()->with('success', 'Vous avez été désinscrit de notre newsletter.');
        }

        return back()->with('error', 'Email non trouvé.');
    }
}
