
{{-- resources/views/avaries/rapport.blade.php --}}
@extends('layouts.app')

@section('title', 'Rapport des Avaries')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Rapport des Avaries par Pointeur</h2>

            {{-- Résumé général --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Résumé Général des Avaries</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Pointeur</th>
                                    <th>Secteur</th>
                                    <th>Nb Total</th>
                                    <th>Montant Total</th>
                                    <th>Moyenne/Avarie</th>
                                    <th>Première</th>
                                    <th>Dernière</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resumeGeneral as $resume)
                                <tr>
                                    <td>{{ $resume->name }}</td>
                                    <td>{{ $resume->secteur }}</td>
                                    <td><span class="badge bg-warning">{{ $resume->total_avaries }}</span></td>
                                    <td class="text-danger fw-bold">{{ number_format($resume->montant_total, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($resume->moyenne_par_avarie, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ \Carbon\Carbon::parse($resume->premiere_avarie)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($resume->derniere_avarie)->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="2">TOTAL GÉNÉRAL</th>
                                    <th>{{ $resumeGeneral->sum('total_avaries') }}</th>
                                    <th class="text-danger">{{ number_format($resumeGeneral->sum('montant_total'), 0, ',', ' ') }} FCFA</th>
                                    <th colspan="3">{{ number_format($resumeGeneral->avg('moyenne_par_avarie'), 0, ',', ' ') }} FCFA (moy.)</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Détails mensuels --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Détails Mensuels {{ date('Y') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Pointeur</th>
                                    <th>Secteur</th>
                                    <th>Mois</th>
                                    <th>Nb Avaries</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $mois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
                                            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                                @endphp
                                @foreach($rapportPointeurs as $rapport)
                                <tr>
                                    <td>{{ $rapport->name }}</td>
                                    <td>{{ $rapport->secteur }}</td>
                                    <td>{{ $mois[$rapport->mois] }} {{ $rapport->annee }}</td>
                                    <td><span class="badge bg-info">{{ $rapport->nombre_avaries }}</span></td>
                                    <td class="text-danger">{{ number_format($rapport->montant_total_avaries, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection