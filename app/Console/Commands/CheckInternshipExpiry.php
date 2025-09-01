<?php

namespace App\Console\Commands;

use App\Models\Stagiaire;
use App\Models\User;
use App\Models\ListenerLog;
use App\Http\Controllers\MessageController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CheckInternshipExpiry extends Command
{
    protected $signature = 'internship:check-expiry';
    protected $description = 'Check for internships expiring within 11 days and notify DG';

    protected $messageController;

    public function __construct(MessageController $messageController)
    {
        parent::__construct();
        $this->messageController = $messageController;
    }

    public function handle()
    {
        $startTime = microtime(true);
        Log::info("CheckInternshipExpiry: Début de la vérification des fins de stage");

        try {
            $now = Carbon::now('Africa/Douala');
            $elevenDaysFromNow = $now->copy()->addDays(11);

            // Récupérer les stagiaires dont la date de fin approche (dans les 11 prochains jours)
            $expiringStagiaires = DB::table('stagiaires')
                ->whereDate('date_fin', '>=', $now->toDateString())
                ->whereDate('date_fin', '<=', $elevenDaysFromNow->toDateString())
                ->get();

            if ($expiringStagiaires->isEmpty()) {
                $message = "Aucun stage arrivant à expiration dans les 11 prochains jours";
                Log::info("CheckInternshipExpiry: $message");
                $this->logExecution('skipped', $message, [], $startTime);
                return 0;
            }

            $details = [];
            foreach ($expiringStagiaires as $stagiaire) {
                $dateFin = Carbon::parse($stagiaire->date_fin);
                $daysRemaining = $now->diffInDays($dateFin, false); // false pour avoir des nombres négatifs si dépassé

                $details[] = [
                    'stagiaire_id' => $stagiaire->id,
                    'nom' => $stagiaire->nom,
                    'prenom' => $stagiaire->prenom,
                    'ecole' => $stagiaire->ecole,
                    'departement' => $stagiaire->departement,
                    'type_stage' => $stagiaire->type_stage,
                    'date_debut' => $stagiaire->date_debut,
                    'date_fin' => $stagiaire->date_fin,
                    'jours_restants' => $daysRemaining
                ];

                Log::info("CheckInternshipExpiry: Stagiaire {$stagiaire->nom} {$stagiaire->prenom} (ID: {$stagiaire->id}) - Fin de stage dans $daysRemaining jours");
            }

            // Construire un message détaillé pour le DG
            $messageContent = $this->buildDetailedMessage($expiringStagiaires, $details);
            
            
            $signalementRequest = new Request([
                'message' => $messageContent,
                'category' => 'report'
            ]);
            
            $this->messageController->store_messageX($signalementRequest);
            $message = "Signalement envoyé au DG pour " . count($expiringStagiaires) . " stage(s) arrivant à expiration";
            Log::info("CheckInternshipExpiry: $message");
            $this->logExecution('success', $message, $details, $startTime);

            return 0;
        } catch (\Exception $e) {
            $message = "Erreur lors de la vérification des fins de stage: " . $e->getMessage();
            Log::error("CheckInternshipExpiry: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function buildDetailedMessage($expiringStagiaires, $details)
    {
        // Récupérer la langue du DG (supposons que le DG a un rôle spécifique ou un ID connu)
        $dgUser = $this->getDGUser();
        $language = $dgUser ? $dgUser->language : 'fr'; // Par défaut français
        
        return $this->formatMessage($expiringStagiaires, $details, $language);
    }

    private function getDGUser()
    {
        return DB::table('users')->where('role', 'dg')->first();
    }

    private function formatMessage($expiringStagiaires, $details, $language)
    {
        $count = count($expiringStagiaires);
        
        if ($language === 'en') {
            return $this->buildEnglishMessage($count, $details);
        } else {
            return $this->buildFrenchMessage($count, $details);
        }
    }

    private function buildFrenchMessage($count, $details)
    {
        $message = "🚨 ALERTE FINS DE STAGE 🚨\n\n";
        $message .= "Bonjour Monsieur le Directeur Général,\n\n";
        $message .= "Nous vous informons que $count stage(s) arrivent à expiration dans les quelques prochains jours :\n\n";

        foreach ($details as $detail) {
            $message .= "👤 {$detail['nom']} {$detail['prenom']}\n";
            $message .= "   📚 École: {$detail['ecole']}\n";
            $message .= "   🏢 Département: {$detail['departement']}\n";
            $message .= "   📋 Type: " . ucfirst($detail['type_stage']) . "\n";
            $message .= "   📅 Fin de stage: {$detail['date_fin']}\n";
            $message .= "   ⏰ Jours restants: {$detail['jours_restants']}\n\n";
        }

        $message .= "📋 ACTIONS REQUISES :\n";
        $message .= "• Préparer les enveloppes de motivation si nécessaire\n";
        $message .= "• Générer automatiquement les attestations de stage via l'application\n";
        $message .= "Cordialement,\n";
        $message .= "EasyGest";

        return $message;
    }

    private function buildEnglishMessage($count, $details)
    {
        $message = "🚨 INTERNSHIP EXPIRY ALERT 🚨\n\n";
        $message .= "Hello Mr. General Director,\n\n";
        $message .= "We inform you that $count internship(s) are expiring within the next few days:\n\n";

        foreach ($details as $detail) {
            $stageType = $detail['type_stage'] === 'academique' ? 'Academic' : 'Professional';
            $message .= "👤 {$detail['nom']} {$detail['prenom']}\n";
            $message .= "   📚 School: {$detail['ecole']}\n";
            $message .= "   🏢 Department: {$detail['departement']}\n";
            $message .= "   📋 Type: $stageType\n";
            $message .= "   📅 End date: {$detail['date_fin']}\n";
            $message .= "   ⏰ Days remaining: {$detail['jours_restants']}\n\n";
        }

        $message .= "📋 REQUIRED ACTIONS:\n";
        $message .= "• Prepare the motivation envelopes if necessary.\n";
        $message .= "• Automatically generate internship certificates through the application.\n";
        $message .= "Best regards,\n";
        $message .= "EasyGest";

        return $message;
    }

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'CheckInternshipExpiry',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}