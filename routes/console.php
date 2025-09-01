<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/


// VII - Mise à jour automatique des heures de départ
Schedule::command('attendance:auto-set-departure')
    ->dailyAt('23:50')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/auto-departure.log'))
    ->onFailure(function () {
        logger()->error('Échec de la mise à jour automatique des heures de départ');
    });

Schedule::command('internship:check-expiry')
    ->cron('0 10 1,11,21 * *') // À 10h les 1er, 11 et 21 de chaque mois
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/internship-check.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des fins de stage');
    });


// I - Déblocage des salaires et avances
Schedule::command('payroll:release-payments')
    ->dailyAt('10:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/payroll-release.log'))
    ->onFailure(function () {
        logger()->error('Échec du déblocage des paiements de salaires');
    });


// IV - Vérification des assignations de matières manquantes
Schedule::command('check:producteur-assignations')
    ->dailyAt('05:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/producteur-assignations-check.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des assignations de matières des producteurs');
    })
    ->onSuccess(function () {
        logger()->info('Vérification des assignations de matières terminée avec succès');
    });

// V - Vérification des matières recommandées manquantes
Schedule::command('materials:check-missing-recommended')
    ->dailyAt('06:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/materials-recommended.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des matières recommandées');
    });


// VIII - Vérification des commandes non validées
Schedule::command('orders:check-unvalidated')
    ->dailyAt('18:10')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/unvalidated-orders.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des commandes non validées à 18h');
});

Schedule::command('orders:check-unvalidated')
    ->dailyAt('20:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/unvalidated-orders.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des commandes non validées à 18h');
});

// IX - Vérification des productions attendues manquantes
Schedule::command('production:check-missing-expected')
    ->dailyAt('05:30')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/missing-expected-production.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des productions attendues manquantes');
    });

// X - Vérification des réceptions de produits manquantes
Schedule::command('reception:check-missing')
    ->dailyAt('21:30')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/missing-reception.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des réceptions manquantes');
    });


// XI - Réinitialisation des flags PIN
Schedule::command('pins:reset-flags')
    ->hourly()
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/pin-reset.log'))
    ->onFailure(function () {
        logger()->error('Échec de la réinitialisation des flags PIN');
    });

// XII - Vérification des seuils de matières (existant)
Schedule::command('materials:check-thresholds')
    ->dailyAt('10:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/materials-thresholds.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des seuils de matières');
    });

  // XIII - Mise à jour des revenus mensuels
Schedule::command('revenue:update-monthly')
->monthlyOn(28, '12:00')
->timezone('Africa/Douala')
->onOneServer()
->runInBackground()
->withoutOverlapping()
->appendOutputTo(storage_path('logs/revenue-update.log'))
->onFailure(function () {
    logger()->error('Échec de la mise à jour des revenus mensuels');
});

Schedule::command('revenue:update-monthly')
->monthlyOn(28, '15:00') // Backup
->timezone('Africa/Douala')
->onOneServer()
->runInBackground()
->withoutOverlapping()
->appendOutputTo(storage_path('logs/revenue-update.log'));

// XIV - Vérification des évaluations d'employés
Schedule::command('evaluations:check-missing')
->dailyAt('14:00')
->timezone('Africa/Douala')
->onOneServer()
->runInBackground()
->withoutOverlapping()
->appendOutputTo(storage_path('logs/employee-evaluations.log'))
->onFailure(function () {
    logger()->error('Échec de la vérification des évaluations employés');
});


// XV - Vérification des problèmes en attente
Schedule::command('issues:check-pending')
->dailyAt('14:30')
->timezone('Africa/Douala')
->onOneServer()
->runInBackground()
->withoutOverlapping()
->appendOutputTo(storage_path('logs/pending-issues.log'))
->onFailure(function () {
    logger()->error('Échec de la vérification des problèmes en attente');
});

// XVI - Expiration des objectifs
Schedule::command('objectives:expire')
    ->dailyAt('02:00') // Choisis l'heure souhaitée, ici 2h du matin
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/objectives-expire.log'))
    ->onFailure(function () {
        logger()->error('Échec de l\'expiration automatique des objectifs');
    });


// XVII - Vérification des recettes manquantes
Schedule::command('recipes:check-missing')
->dailyAt('15:00')
->timezone('Africa/Douala')
->onOneServer()
->runInBackground()
->withoutOverlapping()
->appendOutputTo(storage_path('logs/missing-recipes.log'))
->onFailure(function () {
    logger()->error('Échec de la vérification des recettes manquantes');
});

// XVIII - Vérification du solde CP
Schedule::command('solde:check-cp-balance')
    ->dailyAt('10:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/solde-cp-check.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification du solde CP');
    });

// XIX - Vérification des jours de repos des employés
Schedule::command('employees:check-rest-days')
    ->dailyAt('03:28')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/employee-rest-days.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des jours de repos');
    });
    



// XX - Vérification de l'existence d'objectifs
Schedule::command('objectives:check-existence')
    ->dailyAt('06:38')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/objectives-existence.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification de l\'existence d\'objectifs');
    });


// XXI - Vérification des primes avant jour de paie
Schedule::command('bonuses:check-before-payday')
    ->dailyAt('08:38')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/bonuses-check.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des primes');
    });



// XXII - Vérification des remboursements de dettes avant jour de paie
Schedule::command('loans:check-repayments-before-payday')
    ->dailyAt('08:38')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/loan-repayments-check.log'))
    ->onFailure(function () {
        logger()->error('Échec de la vérification des remboursements de dettes');
    });




// XXIII - Procédures de guidage journalier
Schedule::command('guidance:daily-reminders --feature=flash-recap')
    ->dailyAt('09:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));



Schedule::command('guidance:daily-reminders --feature=assignation-jour')
    ->dailyAt('08:30')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));



Schedule::command('guidance:daily-reminders --feature=achat-depense')
    ->dailyAt('10:10')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=table-production')
    ->dailyAt('10:10')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=performance-produit')
    ->cron('0 9,17 */10 * *') // Chaque 10 jours à 9h et 17h
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=suggerer-production')
    ->dailyAt('05:45')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=finance')
    ->cron('0 10,18 */7 * *') // Chaque 7 jours à 10h et 18h
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=statistiques')
    ->cron('0 11,19 */5 * *') // Chaque 5 jours à 11h et 19h
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=ressource-humaine')
    ->cron('30 11,19 */15 * *') // Chaque 15 jours à 11h30 et 19h30
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=salaires')
    ->cron('30 13,20 */15 * *') // Chaque 15 jours à 13h30 et 20h30
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=sherlock-copilot')
    ->cron('10 10,18 */15 * *') // Chaque 15 jours à 10h10 et 18h15
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));

Schedule::command('guidance:daily-reminders --feature=conseiller-sherlock')
    ->cron('15 10,18 */15 * *') // Chaque 15 jours à 10h10 et 18h15
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-guidance.log'));


        // Vœux saisonniers
Schedule::command('greetings:seasonal --occasion=labor-day')
    ->cron('0 8 1 5 *') // 1er mai à 8h00
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/seasonal-greetings.log'));

Schedule::command('greetings:seasonal --occasion=christmas')
    ->cron('0 8 25 12 *') // 25 décembre à 8h00
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/seasonal-greetings.log'));

Schedule::command('greetings:seasonal --occasion=new-year')
    ->cron('0 8 1 1 *') // 1er janvier à 8h00
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/seasonal-greetings.log'));

Schedule::command('greetings:seasonal --occasion=april-fools')
    ->cron('0 8 1 4 *') // 1er avril à 8h00
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/seasonal-greetings.log'));


//Reste a tester


// XXIV - Procédures de vérification d'incohérence


Schedule::command('checks:inconsistency --type=open-sessions')
    ->dailyAt('04:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/inconsistency-checks.log'));

Schedule::command('checks:inconsistency --type=duplicate-materials')
    ->dailyAt('02:00')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/inconsistency-checks.log'));

Schedule::command('checks:inconsistency --type=duplicate-products')
    ->dailyAt('02:10')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/inconsistency-checks.log'));

Schedule::command('checks:inconsistency --type=unprofitable-production')
    ->dailyAt('02:15')
    ->timezone('Africa/Douala')
    ->onOneServer()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/inconsistency-checks.log'));

Schedule::command('bags:check-stock')
    ->dailyAt('07:00')
    ->timezone('Africa/Douala') // Ajustez selon votre fuseau horaire
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/bags-stock-check.log'));


