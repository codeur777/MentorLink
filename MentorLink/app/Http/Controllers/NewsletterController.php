<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * S'abonner à la newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email',
            'name' => 'nullable|string|max:255'
        ], [
            'email.required' => 'L\'email est requis.',
            'email.email' => 'Veuillez entrer un email valide.',
            'email.unique' => 'Cet email est déjà abonné à notre newsletter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Newsletter::create([
            'email' => $request->email,
            'name' => $request->name,
            'subscribed_at' => now()
        ]);

        return back()->with('newsletter_success', 'Merci ! Vous êtes maintenant abonné à notre newsletter.');
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribe(Request $request)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return redirect('/')->with('error', 'Email manquant pour le désabonnement.');
        }

        $subscription = Newsletter::where('email', $email)->first();
        
        if ($subscription) {
            $subscription->update(['is_active' => false]);
            return view('newsletter.unsubscribed')->with('success', 'Vous avez été désabonné avec succès.');
        }

        return redirect('/')->with('error', 'Email non trouvé dans notre liste.');
    }
}